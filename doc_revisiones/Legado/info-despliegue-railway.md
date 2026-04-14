# Información de Despliegue a Railway

**Fecha**: 2026-03-25
**Agente**: agt-railway-deploy-agent
**Proyecto**: hoja

---

## 1. Información del Proyecto

| Campo | Valor |
|-------|-------|
| **Nombre del proyecto** | hoja |
| **ID del proyecto** | e24d5972-55a9-4e19-99ed-87fc91461ecd |
| **URL del proyecto** | https://railway.com/project/e24d5972-55a9-4e19-99ed-87fc91461ecd |
| **Entorno** | production |
| **ID del entorno** | cd5a2fc0-ce5b-49d0-adc8-7abb9fd8dcf6 |
| **Cuenta Railway** | Plazza City SCR (plazzacity.scr@gmail.com) |

---

## 2. Servicios Creados

### 2.1. Servicio Web (PHP)

| Campo | Valor |
|-------|-------|
| **Nombre del servicio** | web |
| **ID del servicio** | 60af6f83-c5ad-423c-9229-75dc384ee93e |
| **Tipo** | Servicio PHP |
| **Dominio privado** | web.railway.internal |
| **Estado** | Despliegue en progreso |

#### Variables de Entorno del Servicio Web

| Variable | Valor | Sensible |
|----------|--------|-----------|
| APP_ENV | production | No |
| APP_DEBUG | false | No |
| APP_KEY | 9545ab5785aa44807a1c331ae4e9ad39c8c4927d7a0cef36d634db9fbdbbf02d | **Sí** |
| APP_NAME | Hoja - Leaf PHP Application | No |
| DB_CONNECTION | pgsql | No |
| DATABASE_URL | postgresql://postgres:haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN@postgres.railway.internal:5432/railway | **Sí** |
| RAILWAY_ENVIRONMENT | production | No |
| RAILWAY_ENVIRONMENT_ID | cd5a2fc0-ce5b-49d0-adc8-7abb9fd8dcf6 | No |
| RAILWAY_ENVIRONMENT_NAME | production | No |
| RAILWAY_PRIVATE_DOMAIN | web.railway.internal | No |
| RAILWAY_PROJECT_ID | e24d5972-55a9-4e19-99ed-87fc91461ecd | No |
| RAILWAY_PROJECT_NAME | hoja | No |
| RAILWAY_SERVICE_ID | 60af6f83-c5ad-423c-9229-75dc384ee93e | No |
| RAILWAY_SERVICE_NAME | web | No |

### 2.2. Servicio de Base de Datos (PostgreSQL)

| Campo | Valor |
|-------|-------|
| **Nombre del servicio** | Postgres |
| **ID del servicio** | 1caf13f3-23f7-4c79-a389-c7fef044bbef |
| **Tipo** | Base de datos PostgreSQL |
| **Dominio privado** | postgres.railway.internal |
| **Dominio proxy TCP** | hopper.proxy.rlwy.net |
| **Puerto proxy TCP** | 19033 |
| **Base de datos** | railway |
| **Usuario** | postgres |
| **ID del volumen** | 676c990d-4bf3-44aa-97a9-9d8a52316654 |
| **Nombre del volumen** | postgres-volume |
| **Ruta del volumen** | /var/lib/postgresql/data |

#### Variables de Entorno de la Base de Datos

| Variable | Valor | Sensible |
|----------|--------|-----------|
| DATABASE_PUBLIC_URL | postgresql://postgres:haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN@hopper.proxy.rlwy.net:19033/railway | **Sí** |
| DATABASE_URL | postgresql://postgres:haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN@postgres.railway.internal:5432/railway | **Sí** |
| PGDATA | /var/lib/postgresql/data/pgdata | No |
| PGDATABASE | railway | No |
| PGHOST | postgres.railway.internal | No |
| PGPASSWORD | haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN | **Sí** |
| PGPORT | 5432 | No |
| PGUSER | postgres | No |
| POSTGRES_DB | railway | No |
| POSTGRES_PASSWORD | haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN | **Sí** |
| POSTGRES_USER | postgres | No |
| RAILWAY_DEPLOYMENT_DRAINING_SECONDS | 60 | No |
| RAILWAY_ENVIRONMENT | production | No |
| RAILWAY_ENVIRONMENT_ID | cd5a2fc0-ce5b-49d0-adc8-7abb9fd8dcf6 | No |
| RAILWAY_ENVIRONMENT_NAME | production | No |
| RAILWAY_PRIVATE_DOMAIN | postgres.railway.internal | No |
| RAILWAY_PROJECT_ID | e24d5972-55a9-4e19-99ed-87fc91461ecd | No |
| RAILWAY_PROJECT_NAME | hoja | No |
| RAILWAY_SERVICE_ID | 1caf13f3-23f7-4c79-a389-c7fef044bbef | No |
| RAILWAY_SERVICE_NAME | Postgres | No |
| RAILWAY_TCP_APPLICATION_PORT | 5432 | No |
| RAILWAY_TCP_PROXY_DOMAIN | hopper.proxy.rlwy.net | No |
| RAILWAY_TCP_PROXY_PORT | 19033 | No |
| RAILWAY_VOLUME_ID | 676c990d-4bf3-44aa-97a9-9d8a52316654 | No |
| RAILWAY_VOLUME_MOUNT_PATH | /var/lib/postgresql/data | No |
| RAILWAY_VOLUME_NAME | postgres-volume | No |
| SSL_CERT_DAYS | 820 | No |

---

## 3. Configuración del Despliegue

### 3.1. Método de Despliegue

| Campo | Valor |
|-------|-------|
| **Método** | CLI (Railway CLI) |
| **Builder** | DOCKERFILE |
| **Dockerfile** | Dockerfile (corregido para incluir dependencias de MySQL y PostgreSQL) |

### 3.2. Correcciones Realizadas

**Dockerfile**:
- Agregadas dependencias `default-libmysqlclient-dev` y `libpq-dev` para permitir la instalación de extensiones `pdo_mysql` y `pdo_pgsql`

---

## 4. Estado del Despliegue

| Campo | Valor |
|-------|-------|
| **Estado** | En progreso |
| **Última acción** | railway up (segundo intento con Dockerfile corregido) |
| **URL de logs** | https://railway.com/project/e24d5972-55a9-4e19-99ed-87fc91461ecd/service/60af6f83-c5ad-423c-9229-75dc384ee93e?id=a6f2ada3-990b-4c4f-85c5-0e74b8616c28& |

---

## 5. Cambios Recomendados para el Inventario

### 5.1. Sección 0: Método de Despliegue Activo

- Actualizar ID del proyecto Railway
- Actualizar URL del proyecto Railway
- Actualizar estado a "En progreso" o "Desplegado" (según resultado final)
- Actualizar configuración implementada con IDs de servicios

### 5.2. Sección 3: Variables de Entorno

- Agregar variables específicas del servicio web
- Agregar variables específicas de la base de datos PostgreSQL
- Actualizar DATABASE_URL con el valor real de Railway

### 5.3. Sección 4: Secrets (No versionados)

- Actualizar APP_KEY con el valor generado
- Actualizar DATABASE_URL con el valor real de Railway
- Actualizar PGPASSWORD con el valor real de Railway

### 5.4. Historial de Cambios

- Agregar entrada con fecha 2026-03-25
- Documentar creación del proyecto en Railway
- Documentar creación de servicios (web, Postgres)
- Documentar configuración de variables de entorno
- Documentar corrección del Dockerfile

---

## 6. Notas Importantes

1. **Secrets**: Los valores marcados como "Sí" en la tabla de variables de entorno son secrets y no deben ser versionados en el repositorio.
2. **Dockerfile**: Se realizó una corrección para agregar las dependencias necesarias para las extensiones de PHP.
3. **Despliegue**: El despliegue está en progreso y puede tardar varios minutos en completarse.
4. **Base de datos**: La base de datos PostgreSQL está configurada y lista para usarse.
5. **Conexión entre servicios**: El servicio web está conectado a la base de datos PostgreSQL mediante la variable DATABASE_URL.
