# Inventario de Recursos - Hoja

> **Fuente de verdad** para recursos, bindings, variables y configuración operativa del proyecto.
>
> **Regla R15**: Solo el agente `inventariador` puede actualizar este documento.
>
> **Última actualización**: 2026-03-25 - Consolidación de información de SETUP_COMPLETE.md y PROJECT_README.md

---

## Índice de Contenido

1. [Sección 0: Método de Despliegue Activo](#sección-0-método-de-despliegue-activo)
2. [Sección 1: Stack Tecnológico](#sección-1-stack-tecnológico)
   - [Framework y Core](#framework-y-core)
   - [Base de Datos](#base-de-datos)
   - [UI/Template Engine](#uitemplate-engine)
   - [Autenticación](#autenticación)
3. [Sección 2: Recursos Confirmados](#sección-2-recursos-confirmados)
   - [Archivos de Configuración](#archivos-de-configuración)
   - [Archivos de Base de Datos](#archivos-de-base-de-datos)
   - [Modelos y Controladores](#modelos-y-controladores)
   - [Vistas](#vistas)
4. [Sección 3: Variables de Entorno](#sección-3-variables-de-entorno)
   - [Variables Confirmadas](#variables-confirmadas)
   - [Variables Específicas de Producción (Railway)](#variables-específicas-de-producción-railway)
5. [Sección 4: Secrets (No versionados)](#sección-4-secrets-no-versionados)
6. [Sección 5: Endpoints y Contratos](#sección-5-endpoints-y-contratos)
   - [Endpoints Públicos Confirmados](#endpoints-públicos-confirmados)
   - [Endpoints de Usuarios (CRUD)](#endpoints-de-usuarios-crud)
7. [Historial de Cambios](#historial-de-cambios)

---

## Sección 0: Método de Despliegue Activo

| Campo | Valor |
|-------|-------|
| **Plataforma confirmada** | Railway |
| **Método de despliegue** | CLI (Railway CLI) |
| **Agente responsable** | `agt-railway-deploy-agent` |
| **Estado** | ✅ Desplegado en Railway con despliegue funcional |
| **ID del proyecto Railway** | `e24d5972-55a9-4e19-99ed-87fc91461ecd` |
| **URL del proyecto Railway** | https://railway.com/project/e24d5972-55a9-4e19-99ed-87fc91461ecd |
| **Justificación** | Railway soporta PHP nativamente, integración simple con PostgreSQL, despliegue automático desde Git |

**Configuración implementada**:
- ✅ `railway.json` - Configuración de Railway con Dockerfile
- ✅ `Dockerfile` - Imagen de producción optimizada (corregido con dependencias MySQL y PostgreSQL)
- ✅ `Procfile` - Fallback para Railway/Heroku
- ✅ Variables de entorno documentadas en `.env.example`
- ✅ Proyecto creado en Railway con ID: `e24d5972-55a9-4e19-99ed-87fc91461ecd`
- ✅ Servicio web (PHP) creado con ID: `60af6f83-c5ad-423c-9229-75dc384ee93e`
- ✅ Servicio Postgres creado con ID: `1caf13f3-23f7-4c79-a389-c7fef044bbef`
- ✅ Base de datos PostgreSQL creada y conectada
- ✅ Variables de entorno configuradas en Railway

**Método de despliegue actual**:
1. Ejecutar `railway up` desde CLI
2. Railway construye imagen usando `Dockerfile`
3. Despliegue automático con SSL gratuito

**Referencias**:
- `doc_decisiones/despliegue.md` (DEPLOY-001, DEPLOY-002)
- `doc_decisiones/tareas-pendientes.md` (Estado de despliegue verificado)
- `doc_proyecto/concepto-de-proyecto.md`
- `railway.json` (archivo existe)
- `temp/info-despliegue-railway.md` (Información de despliegue)

---

## Sección 1: Stack Tecnológico

### Framework y Core

| Componente | Versión/Estado | Justificación |
|------------|----------------|---------------|
| **Framework** | Leaf PHP | Microframework oficial |
| **HTTP Layer** | `leafs/http` | ✅ Instalado |
| **Seguridad** | `leafs/anchor` | ✅ Instalado (CSRF, validación) |
| **Excepciones** | `leafs/exception` | ✅ Instalado |
| **Base de datos** | `leafs/db` | ✅ Instalado |
| **Dev tools** | `leafs/alchemy` | ✅ Instalado |

**Fuente**: `SETUP_COMPLETE.md`, `PROJECT_README.md`, `composer.json`

### Base de Datos

| Componente | Estado | Notas |
|------------|--------|-------|
| **Producción** | PostgreSQL | ✅ Confirmado para Railway |
| **Desarrollo** | MySQL | ⚠️ Configurado en Docker |
| **Migraciones** | Sistema propio | ✅ `db.php` CLI disponible |
| **Seeders** | Sistema propio | ✅ Incluidos |

**Fuente**: `DATABASE.md`, `doc_proyecto/concepto-de-proyecto.md`, `SETUP_COMPLETE.md`

### UI/Template Engine

| Componente | Estado | Notas |
|------------|--------|-------|
| **UI Framework** | Gentelella (Bootstrap 5) | ✅ Confirmado |
| **Template Engine** | Blade | ⏳ Pendiente de instalación |
| **Arquitectura** | Server-side rendering | ✅ Monolítica |
| **Tipo de aplicación** | Híbrido (API + Views) | ✅ Confirmado |

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:1.1`, `doc_decisiones/ui.md`

### Autenticación

| Componente | Estado | Notas |
|------------|--------|-------|
| **Sistema** | Sesiones + RBAC | ✅ Confirmado para MVP |
| **Middleware** | `AuthMiddleware.php` | ✅ Existe |
| **Almacenamiento** | Redis | ✅ Confirmado (AUTH-002) |
| **Estado JWT** | Ready | ✅ Session + JWT ready |

**Fuente**: `doc_decisiones/autenticacion.md`, `SETUP_COMPLETE.md`, `PROJECT_README.md`

---

## Sección 2: Recursos Confirmados

### Archivos de Configuración

| Archivo | Estado | Propósito |
|---------|--------|-----------|
| `config/app.php` | ✅ Existe | Configuración general |
| `config/database.php` | ✅ Existe | Configuración de BD |
| `routes/web.php` | ✅ Existe | Rutas web |
| `routes/api.php` | ✅ Existe | Rutas API |
| `railway.json` | ✅ Existe | Configuración Railway |
| `docker-compose.yml` | ✅ Existe | Docker desarrollo |
| `Dockerfile` | ✅ Existe | Docker producción |
| `.env.example` | ✅ Existe | Template de entorno |

**Fuente**: `SETUP_COMPLETE.md`, `PROJECT_README.md`

### Archivos de Base de Datos

| Archivo | Estado | Propósito |
|---------|--------|-----------|
| `db.php` | ✅ Existe | CLI para migraciones |
| `database/migrations/*` | ✅ 5 migraciones | Users, password_reset_tokens, sessions, projects, analysis_results |
| `database/seeders/*` | ✅ Seeders incluidos | UserSeeder |
| `DATABASE.md` | ✅ Existe | Documentación de BD |

**Migraciones disponibles**:
1. `2024_01_01_000001_create_users_table.php` - Tabla de usuarios
2. `2024_01_01_000002_create_password_reset_tokens_table.php` - Tokens de recuperación
3. `2024_01_01_000003_create_sessions_table.php` - Sesiones
4. `2024_01_01_000004_create_projects_table.php` - Proyectos de inmuebles
5. `2024_01_01_000005_create_analysis_results_table.php` - Resultados de análisis

**Fuente**: `DATABASE.md`, `SETUP_COMPLETE.md`, `PROJECT_README.md`

### Modelos y Controladores

| Archivo | Estado | Propósito |
|---------|--------|-----------|
| `app/Models/User.php` | ✅ Existe | Modelo de usuario |
| `app/Models/Project.php` | ✅ Existe | Modelo de proyectos de inmuebles |
| `app/Models/AnalysisResult.php` | ✅ Existe | Modelo de resultados de análisis |
| `app/Controllers/HomeController.php` | ✅ Existe | Controlador home |
| `app/Controllers/UserController.php` | ✅ Existe | CRUD de usuarios |
| `app/Middleware/AuthMiddleware.php` | ✅ Existe | Autenticación |
| `app/Middleware/ExampleMiddleware.php` | ✅ Existe | Ejemplo |

**Fuente**: `SETUP_COMPLETE.md`, `PROJECT_README.md`

### File Storage (Almacenamiento)

| Archivo | Estado | Propósito |
|---------|--------|-----------|
| `src/FileStorage/ReportStorage.php` | ✅ Existe | Gestión de informes de análisis (JSON/Markdown) |
| `src/FileStorage/FileUpload.php` | ✅ Existe | Gestión de subida de archivos |
| `storage/reports/` | ✅ Existe | Directorio para informes de análisis |
| `storage/uploads/` | ✅ Existe | Directorio para archivos subidos |

**Fuente**: Implementación FASE 1 - Data Fetching & File System

### Helpers de Sistema

| Helper | Estado | Propósito |
|--------|--------|-----------|
| `db()` | ✅ Existe | Obtener instancia de conexión a BD |
| `database_path()` | ✅ Existe | Obtener ruta de directorio database |
| `storage_path()` | ✅ Existe | Obtener ruta de directorio storage |
| `config()` | ✅ Existe | Obtener valor de configuración |
| `migrate()` | ✅ Existe | Ejecutar migraciones |
| `seed()` | ✅ Existe | Ejecutar seeders |

**Fuente**: `src/Database/Helpers.php`

### Vistas

| Archivo | Estado | Propósito |
|---------|--------|-----------|
| `views/index.view.php` | ✅ Existe | Home page |
| `views/errors/404.view.php` | ✅ Existe | Error 404 |
| `views/errors/500.view.php` | ✅ Existe | Error 500 |

**Fuente**: `SETUP_COMPLETE.md`, `PROJECT_README.md`

---

## Sección 3: Variables de Entorno

### Variables Confirmadas (`.env.example`)

| Variable | Entorno | Sensible | Valor por defecto | Propósito |
|----------|---------|----------|-------------------|-----------|
| `APP_ENV` | Todos | No | `development` | Entorno de la aplicación |
| `APP_DEBUG` | Todos | No | `true` | Modo de depuración |
| `APP_URL` | Todos | No | `http://localhost:8000` | URL base |
| `APP_NAME` | Todos | No | `"Leaf PHP Application"` | Nombre de la app |
| `APP_TIMEZONE` | Todos | No | `UTC` | Timezone |
| `PORT` | Producción | No | - | Puerto (Railway inyecta) |
| `DB_CONNECTION` | Todos | No | `mysql` | Driver de BD |
| `DB_HOST` | Todos | No | `127.0.0.1` | Host de BD |
| `DB_PORT` | Todos | No | `3306` | Puerto de BD |
| `DB_DATABASE` | Todos | No | `leaf_app` | Nombre de BD |
| `DB_USERNAME` | Todos | No | `root` | Usuario de BD |
| `DB_PASSWORD` | Todos | **Sí** | `secret` | Password de BD |
| `DATABASE_URL` | Producción | **Sí** | - | URL de BD (Railway inyecta) |
| `SESSION_DRIVER` | Todos | No | `file` | Driver de sesiones |
| `SESSION_LIFETIME` | Todos | No | `120` | Duración de sesión (min) |
| `SESSION_NAME` | Todos | No | `leaf_session` | Nombre de cookie |
| `SESSION_PATH` | Todos | No | `/tmp/sessions` | Ruta de sesiones |
| `CACHE_DRIVER` | Todos | No | `file` | Driver de caché |
| `CACHE_PATH` | Todos | No | `./cache` | Ruta de caché |
| `CACHE_PREFIX` | Todos | No | `leaf_` | Prefijo de caché |
| `LOG_DRIVER` | Todos | No | `file` | Driver de logs |
| `LOG_LEVEL` | Todos | No | `debug` | Nivel de log |
| `LOG_PATH` | Todos | No | `./storage/logs` | Ruta de logs |
| `LOG_FILE` | Todos | No | `app.log` | Archivo de log |
| `APP_KEY` | Todos | **Sí** | - | Clave de aplicación (generar) |
| `CSRF_ENABLED` | Todos | No | `true` | CSRF habilitado |

### Variables Específicas de Producción (Railway)

| Variable | Entorno | Sensible | Notas |
|----------|---------|----------|-------|
| `DATABASE_URL` | Producción | **Sí** | Inyectado por Railway (PostgreSQL) |
| `APP_ENV` | Producción | No | Debe ser `production` |
| `APP_DEBUG` | Producción | No | Debe ser `false` |
| `APP_NAME` | Producción | No | Hoja - Leaf PHP Application |
| `DB_CONNECTION` | Producción | No | pgsql |
| `RAILWAY_ENVIRONMENT` | Producción | No | production |
| `RAILWAY_ENVIRONMENT_ID` | Producción | No | cd5a2fc0-ce5b-49d0-adc8-7abb9fd8dcf6 |
| `RAILWAY_PRIVATE_DOMAIN` | Producción | No | web.railway.internal |
| `RAILWAY_PROJECT_ID` | Producción | No | e24d5972-55a9-4e19-99ed-87fc91461ecd |
| `RAILWAY_PROJECT_NAME` | Producción | No | hoja |
| `RAILWAY_SERVICE_ID` | Producción | No | 60af6f83-c5ad-423c-9229-75dc384ee93e |
| `RAILWAY_SERVICE_NAME` | Producción | No | web |
| `REDIS_URL` | Producción | **Sí** | Para sesiones y caché de feature toggles |

### Variables Específicas de Base de Datos PostgreSQL (Railway)

| Variable | Entorno | Sensible | Notas |
|----------|---------|----------|-------|
| `DATABASE_PUBLIC_URL` | Producción | **Sí** | URL pública de acceso externo |
| `DATABASE_URL` | Producción | **Sí** | postgresql://postgres:haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN@postgres.railway.internal:5432/railway |
| `PGDATA` | Producción | No | /var/lib/postgresql/data/pgdata |
| `PGDATABASE` | Producción | No | railway |
| `PGHOST` | Producción | No | postgres.railway.internal |
| `PGPASSWORD` | Producción | **Sí** | haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN |
| `PGPORT` | Producción | No | 5432 |
| `PGUSER` | Producción | No | postgres |
| `POSTGRES_DB` | Producción | No | railway |
| `POSTGRES_PASSWORD` | Producción | **Sí** | haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN |
| `POSTGRES_USER` | Producción | No | postgres |
| `RAILWAY_TCP_PROXY_DOMAIN` | Producción | No | hopper.proxy.rlwy.net |
| `RAILWAY_TCP_PROXY_PORT` | Producción | No | 19033 |

**Fuente**: `.env.example`, `SETUP_COMPLETE.md`, `PROJECT_README.md`, `doc_proyecto/concepto-de-proyecto.md`, `temp/info-despliegue-railway.md`

---

## Sección 4: Secrets (No versionados)

| Secret | Almacenamiento | Entorno | Valor (Producción) | Notas |
|--------|----------------|---------|-------------------|-------|
| `DB_PASSWORD` | `.env` (no versionado) | Desarrollo | - | Nunca commitear |
| `DB_PASSWORD` | Railway Secrets | Producción | haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN | Inyectado por Railway |
| `APP_KEY` | `.env` (no versionado) | Todos | 9545ab5785aa44807a1c331ae4e9ad39c8c4927d7a0cef36d634db9fbdbbf02d | Generado por Railway |
| `DATABASE_URL` | Railway Secrets | Producción | postgresql://postgres:haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN@postgres.railway.internal:5432/railway | Inyectado por Railway |
| `PGPASSWORD` | Railway Secrets | Producción | haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN | Inyectado por Railway |
| `REDIS_URL` | Railway Secrets | Producción | - | Para sesiones y caché de feature toggles |

---

## Sección 5: Endpoints y Contratos

### Endpoints Públicos Confirmados

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/` | Welcome |
| GET | `/health` | Health check |
| GET | `/api/info` | API info |
| GET | `/api/v1/health` | API v1 health |
| GET | `/api/v1/info` | API v1 info |
| POST | `/api/v1/echo` | Echo test |

### Endpoints de Usuarios (CRUD)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/users` | Listar usuarios |
| GET | `/api/v1/users/{id}` | Ver usuario |
| POST | `/api/v1/users` | Crear usuario |
| PUT | `/api/v1/users/{id}` | Actualizar usuario |
| DELETE | `/api/v1/users/{id}` | Eliminar usuario |

---

## Historial de Cambios

| Fecha | Cambio | Archivo |
|------|---------|---------|
| 2026-03-25 | **Implementación Data Fetching & File System**: Añadidos modelos Project y AnalysisResult, migraciones 4 y 5, helpers storage_path(), clases ReportStorage y FileUpload | `inventario_recursos.md` |
| 2026-03-25 | Actualización de Sección 0: ID del proyecto Railway (e24d5972-55a9-4e19-99ed-87fc91461ecd), URL del proyecto, IDs de servicios web (60af6f83-c5ad-423c-9229-75dc384ee93e) y Postgres (1caf13f3-23f7-4c79-a389-c7fef044bbef), corrección del Dockerfile | `inventario_recursos.md` |
| 2026-03-25 | Actualización de Sección 3: Variables específicas del servicio web y base de datos PostgreSQL, DATABASE_URL actualizado | `inventario_recursos.md` |
| 2026-03-25 | Actualización de Sección 4: APP_KEY (9545ab5785aa44807a1c331ae4e9ad39c8c4927d7a0cef36d634db9fbdbbf02d), DATABASE_URL y PGPASSWORD (haxbXBzzJgcOfOhxyZLMbbzCJjeoMdYN) | `inventario_recursos.md` |
| 2026-03-25 | Creación del proyecto en Railway con ID: e24d5972-55a9-4e19-99ed-87fc91461ecd | `inventario_recursos.md` |
| 2026-03-25 | Creación de servicios: web (60af6f83-c5ad-423c-9229-75dc384ee93e) y Postgres (1caf13f3-23f7-4c79-a389-c7fef044bbef) | `inventario_recursos.md` |
| 2026-03-25 | Configuración de variables de entorno en Railway | `inventario_recursos.md` |
| 2026-03-25 | Corrección del Dockerfile con dependencias MySQL y PostgreSQL | `inventario_recursos.md` |
| 2026-03-25 | Actualización de Sección 0: Estado de despliegue a "Creado en Railway con despliegue funcional" | `inventario_recursos.md` |
| 2026-03-25 | Adición de configuración implementada: Proyecto creado, BD creada, variables configuradas | `inventario_recursos.md` |
| 2026-03-25 | Consolidación de información de SETUP_COMPLETE.md y PROJECT_README.md | `inventario_recursos.md` |
| 2026-03-25 | Adición de variable DATABASE_URL para Railway | `inventario_recursos.md` |
| 2026-03-25 | Actualización de tipo de aplicación: Híbrido (API + Views) | `inventario_recursos.md` |
| 2026-03-25 | Adición de estado JWT: Session + JWT ready | `inventario_recursos.md` |
