# Configuración - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**:
- doc_respuestas-leaf-docs-researcher/config-deployment-error-handling-di-docker-testing-guard-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/pasos-siguientes-despues-instalacion.md
- SETUP_COMPLETE.md
- PROJECT_README.md

---

## Resumen Ejecutivo

Leaf proporciona un sistema de configuración simple pero potente para gestionar los ajustes de la aplicación. Permite organizar valores de configuración en archivos PHP y acceder a ellos fácilmente desde cualquier parte de la aplicación.

**Decisión Confirmada**: Uso del sistema de configuración de Leaf para el proyecto.

---

## ¿Qué es Config en Leaf?

### Concepto General

Leaf proporciona un sistema de configuración simple pero potente para gestionar los ajustes de tu aplicación. Permite organizar valores de configuración en archivos PHP y acceder a ellos fácilmente desde cualquier parte de la aplicación.

---

## Configuración Básica

### Definición de Configuración

```php
// config/app.php
return [
    'name' => 'Mi Aplicación',
    'debug' => true,
    'timezone' => 'UTC',
];
```

### Acceso a la Configuración

```php
// Usando la función helper config()
$appName = config('app.name');
$debugMode = config('app.debug');

// Con valores por defecto
$timezone = config('app.timezone', 'UTC');
```

---

## Configuración de Entorno

### Integración con Variables de Entorno

Leaf integra bien con variables de entorno:

```php
// config/database.php
return [
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', 3306),
    'database' => env('DB_DATABASE', 'myapp'),
];
```

### Variables de Entorno Disponibles (fuente: SETUP_COMPLETE.md, PROJECT_README.md)

**Variables Críticas de Desarrollo**:

```bash
# .env
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_KEY=<generado-automáticamente>

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leaf_app
DB_USERNAME=root
DB_PASSWORD=secret
```

**Variables de Producción (Railway)**:

```bash
# .env (Producción)
APP_ENV=production
APP_DEBUG=false
PORT=8080
DATABASE_URL=<proporcionado-por-railway>
```

**Variables Adicionales Confirmadas** (fuente: .env.example):

```bash
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_NAME=leaf_session
SESSION_PATH=/tmp/sessions

CACHE_DRIVER=file
CACHE_PATH=./cache
CACHE_PREFIX=leaf_

LOG_DRIVER=file
LOG_LEVEL=debug
LOG_PATH=./storage/logs
LOG_FILE=app.log

APP_NAME="Leaf PHP Application"
APP_TIMEZONE=UTC

CSRF_ENABLED=true
```

### Recomendaciones para Railway

Para despliegue en Railway:

1. **Usar variables de entorno**: Configurar todas las credenciales y URLs como variables de entorno en Railway
2. **Separar configuración por entorno**: Tener configuraciones específicas para desarrollo y producción
3. **No incluir .env en el repositorio**: Usar .env.example como plantilla

---

## Configuración para el Proyecto

### Archivos de Configuración Confirmados

| Archivo | Propósito | Confirmado |
|----------|-----------|------------|
| `config/app.php` | Configuración general de la aplicación | ✅ Sí |
| `config/database.php` | Configuración de base de datos | ✅ Sí |

### Configuración de Base de Datos

**Confirmado desde [`config/database.php`](../config/database.php)**:

```php
return [
    'default' => env('DB_CONNECTION', 'pgsql'),

    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USER', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],
    ],
];
```

---

## Configuración para Despliegue

### Variables de Entorno en Railway

Para despliegue en Railway, se deben configurar las siguientes variables de entorno (fuente: SETUP_COMPLETE.md, PROJECT_README.md):

| Variable | Descripción | Ejemplo |
|----------|-------------|----------|
| `APP_ENV` | Entorno de la aplicación | `production` |
| `APP_DEBUG` | Modo de depuración | `false` |
| `PORT` | Puerto de la aplicación | `8080` (Railway inyecta) |
| `DATABASE_URL` | URL de conexión a BD | `<proporcionado-por-railway>` |
| `APP_KEY` | Clave de aplicación | `<generar-con-openssl>` |

**Nota**: Railway inyecta automáticamente `DATABASE_URL` cuando se crea una base de datos PostgreSQL en el proyecto.

---

## Configuración para Autenticación

### Configuración de Sesiones

Para el sistema de autenticación con sesiones, se debe configurar:

```php
// config/session.php (ejemplo)
return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'connection' => null,
    'table' => 'sessions',
    'store' => null,
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', 'leaf_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
    'same_site' => 'lax',
];
```

### Configuración de Feature Toggles

Para el sistema de feature toggle, se debe configurar:

```php
// config/features.php (ejemplo)
return [
    'projects' => [
        'enabled' => env('FEATURE_PROJECTS', true),
        'access' => ['admin', 'user'],
    ],
    'crm' => [
        'enabled' => env('FEATURE_CRM', false),
        'access' => ['admin'],
    ],
];
```

---

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Sistema de configuración simple | config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Acceso con función helper config() | config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Integración con variables de entorno | config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| config/app.php confirmado | config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| config/database.php confirmado | config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Variables de entorno para Railway | config-deployment-error-handling-di-docker-testing-guard-leaf.md | ✅ Confirmado |
| Variables de entorno de desarrollo documentadas | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |
| Variables de entorno de producción documentadas | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |
| Variables adicionales (SESSION, CACHE, LOG) | .env.example | ✅ Confirmado |

---

## Recomendaciones

### Para Desarrollo

✅ **Usar archivo .env.local**
- Configuración específica para desarrollo local
- No incluir en el repositorio
- Usar .env.example como plantilla

### Para Producción

✅ **Configurar variables de entorno en Railway**
- No incluir .env en el repositorio
- Configurar todas las credenciales como variables de entorno
- Desactivar modo de depuración (`APP_DEBUG=false`)

### Para Seguridad

✅ **Separar configuración por entorno**
- Tener configuraciones específicas para desarrollo y producción
- No incluir credenciales en el código
- Usar variables de entorno para valores sensibles

---

## Referencias a Documentos Fuente

1. **config-deployment-error-handling-di-docker-testing-guard-leaf.md** - Config, Deployment, Error Handling, DI, Docker, Testing, Guard
2. **pasos-siguientes-despues-instalacion.md** - Pasos siguientes después de instalar Leaf PHP
3. **SETUP_COMPLETE.md** - Documentación del setup completado con configuración de entorno
4. **PROJECT_README.md** - README principal del proyecto con configuración
5. **.env.example** - Template de variables de entorno
