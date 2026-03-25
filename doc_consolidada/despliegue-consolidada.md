# Despliegue - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**:
- doc_respuestas-leaf-docs-researcher/config-deployment-error-handling-di-docker-testing-guard-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/analisis-requisitos-proyecto-inmobiliario.md
- doc_proyecto/concepto-de-proyecto.md
- SETUP_COMPLETE.md
- PROJECT_README.md

---

## Resumen Ejecutivo

El proyecto se desplegará en **Railway**, una plataforma PaaS que soporta PHP de forma nativa. Se han identificado múltiples opciones de despliegue, con Railway siendo la opción confirmada para el proyecto.

**Decisiones Confirmadas**:
- ✅ Railway como plataforma de despliegue
- ✅ Deploy a Railway: Preparado (SETUP_COMPLETE.md)
- ✅ Dockerfile + railway.json listos
- ✅ Proyecto creado en Railway con despliegue funcional

---

## Información Confirmada

### Plataforma de Despliegue

| Aspecto | Confirmado |
|---------|-----------|
| **Plataforma** | Railway |
| **Framework** | Leaf PHP |
| **Base de datos** | PostgreSQL (Railway), MySQL (Docker desarrollo) |
| **UI** | Gentelella (Bootstrap 5 Admin Dashboard) |
| **Arquitectura** | Monolítica (backend + frontend juntos) |
| **Estado** | ✅ Creado en Railway con despliegue funcional |

### Ventajas de Railway para Leaf

- ✅ Soporte nativo para PHP
- ✅ Integración con bases de datos (PostgreSQL, MySQL)
- ✅ Variables de entorno fáciles de configurar
- ✅ Despliegue automático desde Git
- ✅ SSL gratuito
- ✅ Escalado horizontal automático

---

## Opciones de Despliegue

### 1. Railway (Opción Confirmada)

**Configuración para Railway** (fuente: SETUP_COMPLETE.md, railway.json):

```json
{
  "build": {
    "builder": "DOCKERFILE",
    "dockerfilePath": "Dockerfile"
  },
  "deploy": {
    "startCommand": "/usr/local/bin/entrypoint",
    "healthcheckPath": "/health",
    "healthcheckTimeout": 100,
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

**Variables de Entorno en Railway**:
- `APP_ENV`: `production`
- `APP_DEBUG`: `false`
- `PORT`: `8080` (Railway inyecta)
- `DATABASE_URL`: `<proporcionado-por-railway>` (PostgreSQL)
- `APP_KEY`: `<generar-con-openssl>`

**Configuración Implementada**:
- ✅ `railway.json` - Configuración de Railway con Dockerfile
- ✅ `Dockerfile` - Imagen de producción optimizada
- ✅ `Dockerfile.dev` - Imagen de desarrollo con Xdebug
- ✅ `docker-compose.yml` - Servicios múltiples
- ✅ `Procfile` - Fallback para Railway/Heroku
- ✅ `docker/nginx/default.conf` - Nginx config
- ✅ `docker/mysql/init.sql` - Init de MySQL

**Método de Despliegue Actual**:
1. Push de código a GitHub
2. Railway detecta cambios automáticamente
3. Railway construye imagen usando `Dockerfile`
4. Despliegue automático con SSL gratuito

**Nota**: Railway inyecta automáticamente `DATABASE_URL` cuando se crea una base de datos PostgreSQL en el proyecto.

**Ventajas**:
- Despliegue simple (un solo proyecto)
- Menor complejidad de infraestructura
- Menor costo potencial (un solo servicio)
- SSL gratuito
- Escalado automático

**Consideraciones**:
- Para sesiones: requiere almacenamiento compartido (Redis) si escala horizontalmente
- Para JWT: escala mejor sin sticky sessions

### 2. Docker

**Dockerfile para producción** (fuente: SETUP_COMPLETE.md, Dockerfile):

El proyecto incluye dos Dockerfiles:
- `Dockerfile` - Imagen de producción optimizada
- `Dockerfile.dev` - Imagen de desarrollo con Xdebug

**docker-compose.yml** (fuente: SETUP_COMPLETE.md, docker-compose.yml):

Servicios disponibles con perfiles:

| Perfil | Servicios incluidos | Comando |
|--------|---------------------|---------|
| `with-db` | App + MySQL | `docker-compose --profile with-db up -d` |
| `with-cache` | App + Redis | `docker-compose --profile with-cache up -d` |
| `with-mail` | App + MailHog | `docker-compose --profile with-mail up -d` |
| Todos | App + MySQL + Redis + MailHog | `docker-compose --profile with-db --profile with-cache --profile with-mail up -d` |

**Comandos útiles**:

```bash
# Iniciar todos los servicios
docker-compose up -d

# Acceder al contenedor
docker-compose exec app bash

# Ver logs
docker-compose logs -f app

# Ejecutar migraciones en Docker
docker-compose exec app composer db:fresh
```

**Ventajas**:
- Control total sobre el entorno
- Reproducibilidad exacta
- Ideal para desarrollo local

**Desventajas**:
- Mayor complejidad de gestión
- Requiere conocimientos de Docker

### 3. VPS/Shared Hosting

**Requisitos mínimos**:
- PHP 8.0 o superior
- Composer
- Servidor web (Nginx/Apache) con configuración para PHP

**Configuración de Nginx**:

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

**Ventajas**:
- Control total del servidor
- Costo potencialmente menor

**Desventajas**:
- Mayor responsabilidad de gestión
- Requiere conocimientos de administración de servidores

---

## Checklist de Despliegue

### Antes de Desplegar a Producción

1. ✅ Configurar `APP_ENV=production`
2. ✅ Desactivar `APP_DEBUG=false`
3. ✅ Configurar variables de entorno
4. ✅ Ejecutar migraciones de base de datos
5. ✅ Optimizar Composer: `composer install --no-dev --optimize-autoloader`
6. ✅ Configurar cache de configuración
7. ✅ Configurar logging apropiado
8. ✅ Verificar permisos de archivos
9. ✅ Configurar SSL/HTTPS
10. ✅ Probar rutas críticas

---

## Configuración de Entorno

### Variables de Entorno Disponibles

```bash
# .env
APP_ENV=production
APP_DEBUG=false
DB_HOST=localhost
DB_DATABASE=myapp
DB_USER=root
DB_PASS=secret
```

### Recomendaciones para Railway

1. **Usar variables de entorno**: Configurar todas las credenciales y URLs como variables de entorno en Railway
2. **Separar configuración por entorno**: Tener configuraciones específicas para desarrollo y producción
3. **No incluir .env en el repositorio**: Usar .env.example como plantilla

---

## Consideraciones de Escalabilidad

### Sesiones en Railway

**Problema**: Railway escala horizontalmente automáticamente. Si usas sesiones almacenadas en archivos, cada instancia tendrá su propio almacenamiento de sesiones.

**Soluciones**:
1. **Usar Redis**: Almacenamiento compartido de sesiones
2. **Usar base de datos**: Almacenar sesiones en PostgreSQL
3. **Sticky sessions**: Configurar Railway para que las solicitudes del mismo usuario vayan a la misma instancia (reduce eficiencia del balanceador de carga)

### JWT en Railway

**Ventaja**: JWT escala mejor sin sticky sessions porque el token se valida localmente sin necesidad de acceder a almacenamiento compartido.

---

## Información Específica por Tipo de Autenticación

### Sesiones

| Aspecto | Consideración para Railway |
|---------|---------------------------|
| Escalabilidad | Requiere almacenamiento compartido (Redis o BD) |
| Sticky sessions | Necesarias si no hay almacenamiento compartido |
| Costo | Redis adicional si se escala horizontalmente |

### JWT

| Aspecto | Consideración para Railway |
|---------|---------------------------|
| Escalabilidad | Escala mejor sin sticky sessions |
| Sticky sessions | No necesarias |
| Costo | Menor costo potencial (sin Redis para sesiones) |

---

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Railway como plataforma de despliegue | concepto-de-proyecto.md, analisis-requisitos-proyecto-inmobiliario.md | ✅ Confirmado |
| Deploy a Railway: Preparado | SETUP_COMPLETE.md | ✅ Confirmado |
| railway.json configuración | SETUP_COMPLETE.md, config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Variables de entorno en Railway | SETUP_COMPLETE.md, config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Dockerfile para producción | SETUP_COMPLETE.md, config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Dockerfile.dev para desarrollo | SETUP_COMPLETE.md | ✅ Confirmado |
| docker-compose.yml con perfiles | SETUP_COMPLETE.md | ✅ Confirmado |
| Procfile para fallback | SETUP_COMPLETE.md | ✅ Confirmado |
| Proyecto creado en Railway con despliegue funcional | SETUP_COMPLETE.md | ✅ Confirmado |
| Nginx configuration | SETUP_COMPLETE.md, config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Checklist de despliegue | config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |

---

## Recomendaciones

### Para MVP

✅ **Usar Railway**
- Despliegue simple (un solo proyecto)
- Menor complejidad de infraestructura
- Escalado automático cuando sea necesario

### Para Sesiones

⏳ **Planear almacenamiento compartido**
- Para MVP: almacenamiento en archivos es suficiente
- Para escalado: Redis o base de datos para sesiones

### Para Futuro

⏳ **Evaluar alternativas si:**
- El costo de Railway es demasiado alto
- Necesitas más control sobre el entorno
- Requisitos específicos de seguridad o compliance

---

## Referencias a Documentos Fuente

1. **config-deployment-error-handling-di-docker-testing-guard-leaf.md** - Configuración, despliegue, error handling, DI, Docker, testing, Guard
2. **analisis-requisitos-proyecto-inmobiliario.md** - Análisis de requisitos del proyecto
3. **concepto-de-proyecto.md** - Concepto general del proyecto
4. **SETUP_COMPLETE.md** - Documentación del setup completado con despliegue a Railway
5. **PROJECT_README.md** - README principal del proyecto con información de despliegue
6. **railway.json** - Configuración de Railway
7. **Dockerfile** - Dockerfile de producción
8. **Dockerfile.dev** - Dockerfile de desarrollo
9. **docker-compose.yml** - Configuración de Docker Compose
10. **Procfile** - Fallback para Railway/Heroku
