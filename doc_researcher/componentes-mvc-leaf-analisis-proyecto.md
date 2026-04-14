# Componentes MVC de Leaf PHP - Análisis para el Proyecto Hoja

**Fecha de investigación**: 2026-03-25
**Agente**: `agt-doc-researcher` (simulado)
**Fuentes**: Documentación interna del proyecto, `doc_consolidada/`, `SETUP_COMPLETE.md`, `ANALYSIS_AND_ADOPTION_PLAN.md`

---

## Resumen Ejecutivo

Este documento analiza los componentes MVC y herramientas de desarrollo de Leaf PHP y evalúa su papel potencial para el proyecto Hoja (gestión de inmuebles con análisis IA). La documentación interna confirma el uso de arquitectura MVC con Leaf PHP.

---

## Clasificación de la Información

| Tipo | Descripción |
|------|-------------|
| **HECHO** | Información explícitamente documentada en `doc_consolidada/` y archivos del proyecto |
| **OBSERVACIÓN** | Lo que se infiere de la estructura actual del repositorio |
| **INFERENCIA** | Conclusión razonable basada en requisitos del proyecto |
| **NO_CONFIRMADO** | Información no encontrada en documentación interna |

---

## 1. Building to Scale (Construir para Escalar)

### Qué es/Hace

**HECHO**: Prácticas y patrones para construir aplicaciones Leaf PHP que puedan escalar en producción.

**Componentes clave**:
- Estructura de carpetas organizada
- Separación clara de responsabilidades (MVC)
- Uso apropiado de middleware
- Configuración externalizada
- Sistema de caché
- Base de datos optimizada
- Logging apropiado

### Uso en Leaf

**OBSERVACIÓN**: El proyecto ya tiene estructura para escalar:

```
/workspaces/hoja/
├── app/
│   ├── Controllers/     # Controladores
│   ├── Middleware/      # Middleware (AuthMiddleware.php)
│   └── Models/          # Modelos
├── config/              # Configuración externalizada
│   ├── app.php
│   └── database.php
├── database/
│   ├── migrations/      # Migraciones numeradas
│   └── seeders/         # Seeders
├── routes/              # Rutas separadas
│   ├── api.php
│   └── web.php
├── storage/             # Almacenamiento
│   ├── logs/
│   └── uploads/
└── views/               # Vistas
```

**Fuente**: `SETUP_COMPLETE.md`, `ANALYSIS_AND_ADOPTION_PLAN.md`

### Papel para el Proyecto Hoja

**HECHO - Utilidad ALTA**:

| Componente | Utilidad para Hoja | Justificación |
|------------|-------------------|---------------|
| Estructura MVC | ✅ Alta | Confirmada en `doc_proyecto/concepto-de-proyecto.md` |
| Middleware | ✅ Alta | `AuthMiddleware.php` ya existe |
| Configuración externalizada | ✅ Alta | `config/app.php`, `.env.example` existen |
| Migraciones | ✅ Alta | 3 migraciones ya creadas |
| Logging | ✅ Media | `storage/logs/` configurado |

**Recomendación**:
- **MVP**: Mantener estructura actual (ya está preparada para escalar)
- **Futuro**: Añadir caché (Redis) si hay problemas de rendimiento

**Fuentes**:
- `doc_proyecto/concepto-de-proyecto.md:4` (Arquitectura MVC confirmada)
- `SETUP_COMPLETE.md` (estructura del proyecto)
- `doc_consolidada/arquitectura-mvc-consolidada.md`

---

## 2. Controllers (Controladores)

### Qué es/Hace

**HECHO**: Los controllers conectan models y views, manejan la lógica de la aplicación y coordinan las respuestas a las solicitudes del usuario.

**Responsabilidades**:
- Recibir solicitudes HTTP
- Validar datos de entrada
- Coordinar operaciones con models
- Seleccionar la vista apropiada
- Retornar respuestas HTTP

### Uso en Leaf

**HECHO**: El proyecto ya tiene controladores implementados:

```php
// app/Controllers/HomeController.php
class HomeController {
    public function index() {
        return response()->json([
            'message' => 'Welcome to Leaf PHP',
            'version' => '1.0.0'
        ]);
    }
}

// app/Controllers/UserController.php
class UserController {
    public function index() {
        $users = (new User())->all();
        return response()->json(['data' => $users]);
    }
    
    public function store() {
        $data = request()->json()->all();
        // Validar y crear usuario
        $userId = (new User())->create($data);
        return response()->json(['data' => $userId], 201);
    }
}
```

**Controladores confirmados**:
- `HomeController.php` - Home page
- `UserController.php` - CRUD completo de usuarios

**Fuente**: `SETUP_COMPLETE.md`, `doc_consolidada/arquitectura-mvc-consolidada.md`

### Papel para el Proyecto Hoja

**HECHO - Utilidad ALTA**:

| Controller | Utilidad para Hoja | Estado |
|------------|-------------------|--------|
| HomeController | ✅ Media | ✅ Existe (básico) |
| UserController | ✅ Alta | ✅ Existe (CRUD completo) |
| ProjectController | ✅ Alta | ⏳ Pendiente (recurso core del MVP) |
| AuthController | ✅ Alta | ⏳ Pendiente (login/logout) |
| AnalysisController | ✅ Alta | ⏳ Pendiente (workflows OpenAI) |

**Recomendación**:
- **MVP**: Crear `ProjectController` y `AuthController`
- **Futuro**: Crear `AnalysisController` para workflows de OpenAI

**Fuentes**:
- `doc_proyecto/concepto-de-proyecto.md:3` (Controllers en `app/Controllers/`)
- `SETUP_COMPLETE.md` (controladores existentes)

---

## 3. Models (Modelos)

### Qué es/Hace

**HECHO**: Los models manejan los datos y la lógica de negocio relacionada con las entidades del dominio.

**Responsabilidades**:
- Definir la estructura de datos de las entidades
- Contener la lógica de negocio para operaciones sobre entidades
- Interactuar con la base de datos
- Validar datos antes de persistir

### Uso en Leaf

**HECHO**: El proyecto ya tiene el modelo User implementado:

```php
// app/Models/User.php
class User {
    protected $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    public function all() {
        return $this->db->query('SELECT * FROM users')->fetchAll();
    }
    
    public function find($id) {
        return $this->db->query('SELECT * FROM users WHERE id = ?', [$id])->fetch();
    }
    
    public function create($data) {
        $this->db->query(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)',
            [$data['name'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT)]
        );
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $this->db->query(
            'UPDATE users SET name = ?, email = ? WHERE id = ?',
            [$data['name'], $data['email'], $id]
        );
    }
    
    public function delete($id) {
        $this->db->query('DELETE FROM users WHERE id = ?', [$id]);
    }
}
```

**Modelos confirmados**:
- `User.php` - Modelo de usuario completo

**Fuente**: `SETUP_COMPLETE.md`, `DATABASE.md`

### Papel para el Proyecto Hoja

**HECHO - Utilidad ALTA**:

| Modelo | Utilidad para Hoja | Estado |
|--------|-------------------|--------|
| User | ✅ Alta | ✅ Existe (completo) |
| Project | ✅ Alta | ⏳ Pendiente (entidad core del MVP) |
| Analysis | ✅ Alta | ⏳ Pendiente (workflows OpenAI) |
| Report | ✅ Media | ⏳ Pendiente (informes de análisis) |
| Role | ✅ Media | ⏳ Pendiente (RBAC) |
| Permission | ✅ Media | ⏳ Pendiente (RBAC) |

**Recomendación**:
- **MVP**: Crear `Project` modelo inmediatamente
- **Corto plazo**: Crear modelos de RBAC (`Role`, `Permission`)
- **Futuro**: Crear `Analysis` y `Report` models

**Fuentes**:
- `doc_proyecto/concepto-de-proyecto.md:3` (Models en `app/Models/`)
- `DATABASE.md` (modelo User y estructura de BD)

---

## 4. Schema Files (Archivos de Esquema)

### Qué es/Hace

**HECHO**: Los schema files definen la estructura de la base de datos mediante migraciones numeradas.

**Características**:
- Archivos PHP en `database/migrations/`
- Numerados cronológicamente (`YYYY_MM_DD_HHMMSS_description.php`)
- Métodos `up()` (crear) y `down()` (revertir)
- Idempotentes (pueden ejecutarse múltiples veces)

### Uso en Leaf

**HECHO**: El proyecto ya tiene 3 migraciones creadas:

```
database/migrations/
├── 2024_01_01_000001_create_users_table.php
├── 2024_01_01_000002_create_password_reset_tokens_table.php
└── 2024_01_01_000003_create_sessions_table.php
```

**Estructura de migración**:
```php
<?php

namespace Database\Migrations;

use Leaf\Db;

class CreateUsersTable
{
    protected Db $db;

    public function __construct() {
        $this->db = db();
    }

    public function up(): void {
        $this->db->query("
            CREATE TABLE users (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down(): void {
        $this->db->query("DROP TABLE IF EXISTS users");
    }
}
```

**Fuente**: `DATABASE.md`, `SETUP_COMPLETE.md`

### Papel para el Proyecto Hoja

**HECHO - Utilidad ALTA**:

| Migración | Utilidad para Hoja | Estado |
|-----------|-------------------|--------|
| create_users_table | ✅ Alta | ✅ Existe |
| create_password_reset_tokens_table | ⚠️ Media | ✅ Existe (no crítica para MVP) |
| create_sessions_table | ✅ Alta | ✅ Existe |
| create_projects_table | ✅ Alta | ⏳ Pendiente (crítica para MVP) |
| create_roles_table | ✅ Media | ⏳ Pendiente (RBAC) |
| create_permissions_table | ✅ Media | ⏳ Pendiente (RBAC) |
| create_feature_toggles_table | ✅ Media | ⏳ Pendiente (FT-001) |

**Recomendación**:
- **MVP**: Crear migración `create_projects_table` inmediatamente
- **Corto plazo**: Crear migraciones de RBAC
- **Futuro**: Crear migración de feature toggles

**Fuentes**:
- `DATABASE.md` (sistema de migraciones)
- `doc_decisiones/feature-toggle.md` (FT-001: Feature Toggle tables)

---

## 5. Services (Servicios)

### Qué es/Hace

**NO_CONFIRMADO**: Leaf no tiene un módulo oficial de "Services" como capa separada.

**INFERENCIA**: Los services son una capa opcional de lógica de negocio que separa operaciones complejas de los controllers.

**Cuándo usar**:
- Lógica de negocio compleja que no pertenece a models
- Operaciones que involucran múltiples models
- Integración con servicios externos (ej: OpenAI API)
- Operaciones que podrían reutilizarse

### Uso en Leaf

**NO_CONFIRMADO**: No hay documentación explícita sobre services en el proyecto.

**Estructura típica**:
```php
// app/Services/ProjectAnalysisService.php
class ProjectAnalysisService {
    protected $openaiClient;
    
    public function analyzeProject($projectId) {
        // 1. Obtener proyecto
        $project = (new Project())->find($projectId);
        
        // 2. Ejecutar workflow de OpenAI (9 pasos)
        $analysis = $this->openaiClient->analyze($project);
        
        // 3. Guardar informe
        $this->saveReport($projectId, $analysis);
        
        return $analysis;
    }
}
```

### Papel para el Proyecto Hoja

**INFERENCIA - Utilidad MEDIA-ALTA**:

| Servicio | Utilidad para Hoja | Justificación |
|----------|-------------------|---------------|
| ProjectAnalysisService | ✅ Alta | Workflow de 9 pasos con OpenAI API |
| AuthenticationService | ⚠️ Media | Login/logout con sesiones |
| FeatureToggleService | ⚠️ Media | Gestión de feature toggles |
| RBACService | ⚠️ Media | Verificación de permisos |

**Recomendación**:
- **MVP**: No crear capa de services (controllers pueden manejar lógica simple)
- **Futuro**: Crear `ProjectAnalysisService` cuando se implemente OpenAI API
  - Separa lógica compleja de controllers
  - Facilita testing unitario
  - Permite reutilización

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:1` (Ejecución de análisis de inmueble mediante OpenAI API - workflow de 9 pasos)

---

## 6. Writing Commands (Escribir Comandos)

### Qué es/Hace

**HECHO**: Leaf proporciona herramientas CLI (Command Line Interface) para interactuar con la aplicación.

**Tipos de comandos**:
- Comandos de base de datos (migraciones, seeders)
- Comandos personalizados (tareas programadas, workers)
- Comandos de desarrollo (servidor, testing)

### Uso en Leaf

**HECHO**: El proyecto ya tiene comandos disponibles:

```bash
# Comandos de base de datos
php db.php migrate           # Ejecutar migraciones
php db.php migrate:rollback  # Revertir migraciones
php db.php migrate:status    # Ver estado
php db.php seed              # Ejecutar seeders
php db.php fresh             # Fresh migration
php db.php fresh:seed        # Fresh + seeders

# Comandos de desarrollo
composer serve               # Iniciar servidor
composer run test            # Ejecutar tests
composer run lint            # Ejecutar linter
```

**Archivo de comandos**: `db.php` - CLI para operaciones de BD

**Fuente**: `DATABASE.md`, `SETUP_COMPLETE.md`

### Papel para el Proyecto Hoja

**HECHO - Utilidad ALTA**:

| Comando | Utilidad para Hoja | Estado |
|---------|-------------------|--------|
| migrate | ✅ Alta | ✅ Existe |
| seed | ✅ Alta | ✅ Existe |
| fresh | ✅ Media | ✅ Existe |
| Comandos personalizados | ⚠️ Media | ⏳ Pendiente |

**Recomendación**:
- **MVP**: Usar comandos existentes (suficientes)
- **Futuro**: Crear comandos personalizados para:
  - `php hoja analyze:all` - Ejecutar análisis de todos los proyectos
  - `php hoja cache:clear` - Limpiar caché
  - `php hoja workflow:run` - Ejecutar worker de colas (si se implementan)

**Fuentes**:
- `DATABASE.md` (comandos de BD)
- `SETUP_COMPLETE.md:116-130` (comandos disponibles)

---

## 7. MVC Globals (Globales de MVC)

### Qué es/Hace

**HECHO**: Leaf proporciona funciones globales helper para acceder rápidamente a componentes de la aplicación.

**Funciones globales comunes**:
- `app()` - Obtener instancia de la aplicación
- `request()` - Obtener objeto request
- `response()` - Obtener objeto response
- `db()` - Obtener instancia de base de datos
- `config()` - Obtener valor de configuración
- `view()` - Renderizar vista

### Uso en Leaf

**HECHO**: El proyecto ya usa funciones globales:

```php
// En controllers
use Leaf\Db;

$users = Db::select('SELECT * FROM users');

// O usando función global
$users = db()->query('SELECT * FROM users')->fetchAll();

// Acceder a configuración
$appName = config('app.name');
$debugMode = config('app.debug');

// Request y Response
$data = request()->json()->all();
return response()->json(['data' => $data]);
```

**Fuente**: `SETUP_COMPLETE.md`, `config/app.php`

### Papel para el Proyecto Hoja

**HECHO - Utilidad ALTA**:

| Función Global | Utilidad para Hoja | Uso Confirmado |
|----------------|-------------------|----------------|
| `app()` | ✅ Alta | Para acceder a contexto de aplicación |
| `request()` | ✅ Alta | ✅ Usado en `UserController.php` |
| `response()` | ✅ Alta | ✅ Usado en controllers |
| `db()` | ✅ Alta | ✅ Usado en modelos |
| `config()` | ✅ Alta | Para configuración de aplicación |
| `view()` | ⚠️ Media | Para renderizar vistas (Gentelella) |

**Recomendación**:
- **MVP**: Continuar usando funciones globales (ya configuradas)
- **Futuro**: Considerar crear helpers personalizados para:
  - `user()` - Obtener usuario autenticado
  - `feature($name)` - Verificar feature toggle
  - `can($permission)` - Verificar permiso RBAC

**Fuentes**:
- `SETUP_COMPLETE.md` (uso en controllers)
- `config/app.php` (configuración)

---

## 8. Custom Libraries (Librerías Personalizadas)

### Qué es/Hace

**NO_CONFIRMADO**: Leaf no tiene un sistema oficial de "Custom Libraries" como Laravel.

**INFERENCIA**: Las librerías personalizadas son clases utilitarias que proporcionan funcionalidad reutilizable fuera del patrón MVC.

**Cuándo usar**:
- Funcionalidad que no es ni model, ni controller, ni view
- Utilidades reutilizables en múltiples partes de la aplicación
- Integración con APIs externas (ej: OpenAI client wrapper)

### Uso en Leaf

**NO_CONFIRMADO**: No hay documentación explícita sobre librerías personalizadas en el proyecto.

**Estructura típica**:
```
app/
├── Libraries/
│   ├── OpenAIClient.php      # Wrapper para API de OpenAI
│   ├── FeatureToggle.php     # Gestión de feature toggles
│   └── RBAC.php              # Verificación de permisos
```

### Papel para el Proyecto Hoja

**INFERENCIA - Utilidad MEDIA**:

| Librería | Utilidad para Hoja | Justificación |
|----------|-------------------|---------------|
| OpenAIClient | ✅ Alta | Wrapper para workflows de análisis |
| FeatureToggle | ⚠️ Media | Gestión de activación de funcionalidades |
| RBAC | ⚠️ Media | Verificación de permisos |
| ImageProcessor | ⚠️ Baja | Procesamiento de imágenes de inmuebles |

**Recomendación**:
- **MVP**: No crear librerías personalizadas (puede esperar)
- **Futuro**: Crear `OpenAIClient` como librería personalizada
  - Encapsula lógica de comunicación con OpenAI API
  - Facilita testing (mock del cliente)
  - Permite cambiar de proveedor si es necesario

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:1` (Ejecución de análisis de inmueble mediante OpenAI API)

---

## 9. MVC Console Tool (Herramienta de Consola MVC)

### Qué es/Hace

**HECHO**: Leaf CLI es la herramienta de línea de comandos oficial para crear y interactuar con aplicaciones Leaf.

**Funcionalidades**:
- Crear nueva aplicación Leaf (`leaf new`)
- Iniciar servidor de desarrollo (`leaf serve`)
- Generar scaffolding (controllers, models, migrations)
- Ejecutar comandos personalizados

### Uso en Leaf

**HECHO**: El proyecto menciona Leaf CLI pero usa Composer scripts:

```bash
# Leaf CLI (si está instalado)
leaf new my-project
leaf serve

# Composer scripts (usado en el proyecto)
composer serve
composer run test
composer run lint
composer db:migrate
```

**Referencia**: 
> "You can create a new Leaf app using the [Leaf CLI](https://leafphp.dev/docs/cli/)"
> — `README.md:74`

**Fuente**: `README.md`, `ANALYSIS_AND_ADOPTION_PLAN.md`, `SETUP_COMPLETE.md`

### Papel para el Proyecto Hoja

**HECHO - Utilidad BAJA-MEDIA**:

| Funcionalidad | Utilidad para Hoja | Estado |
|---------------|-------------------|--------|
| leaf new | ❌ No aplica | Proyecto ya creado |
| leaf serve | ⚠️ Baja | `composer serve` ya funciona |
| leaf make:controller | ⚠️ Media | Podría agilizar creación |
| leaf make:model | ⚠️ Media | Podría agilizar creación |
| leaf make:migration | ⚠️ Media | Podría agilizar creación |

**Recomendación**:
- **MVP**: Continuar usando Composer scripts (ya configurados)
- **Futuro**: Evaluar instalación de Leaf CLI si:
  - El equipo necesita generar scaffolding frecuentemente
  - Se requieren comandos personalizados complejos
  - Hay múltiples desarrolladores en el equipo

**Alternativa actual**: Los scripts de Composer en `composer.json` proporcionan funcionalidad similar:
```json
{
  "scripts": {
    "serve": "php -S localhost:8000 -t public",
    "db:migrate": "php db.php migrate",
    "db:seed": "php db.php seed"
  }
}
```

**Fuentes**:
- `README.md:74, 94` (Leaf CLI mencionado)
- `ANALYSIS_AND_ADOPTION_PLAN.md:245` (referencia a Leaf CLI)
- `SETUP_COMPLETE.md:116-130` (Composer scripts)

---

## Tabla Resumen de Recomendaciones

| Componente | Utilidad MVP | Recomendación | Prioridad |
|------------|--------------|---------------|-----------|
| **Building to Scale** | ✅ Alta | Mantener estructura actual | Alta |
| **Controllers** | ✅ Alta | Crear ProjectController, AuthController | Alta |
| **Models** | ✅ Alta | Crear Project modelo | Alta |
| **Schema Files** | ✅ Alta | Crear migración de projects | Alta |
| **Services** | ⚠️ Media | No crear para MVP, evaluar después | Media (futuro) |
| **Writing Commands** | ✅ Alta | Usar existentes, agregar personalizados después | Media |
| **MVC Globals** | ✅ Alta | Continuar usando | Alta |
| **Custom Libraries** | ⚠️ Media | Crear OpenAIClient en el futuro | Media (futuro) |
| **MVC Console Tool** | ⚠️ Baja | Continuar con Composer scripts | Baja |

---

## Decisiones Pendientes Relacionadas

| ID | Decisión | Tema | Impacto | Archivo |
|----|----------|------|---------|---------|
| FT-001 | Sistema de Feature Toggle | Feature Toggle | ALTO | `doc_decisiones/feature-toggle.md` |
| AUTH-002 | Almacenamiento de sesiones | Autenticación | MEDIO | `doc_decisiones/autenticacion.md` |
| MOD-002 | Sistema de colas (fase posterior) | Módulos | BAJO | `doc_decisiones/modulos.md` |

---

## Conclusión

De los 9 componentes analizados:

**Para MVP (confirmados/críticos)**:
- ✅ **Building to Scale**: Estructura actual ya está preparada
- ✅ **Controllers**: User existe, crear Project y Auth
- ✅ **Models**: User existe, crear Project
- ✅ **Schema Files**: 3 migraciones existen, crear projects
- ✅ **Writing Commands**: Comandos de BD ya funcionan
- ✅ **MVC Globals**: Ya se usan en controllers

**Para evaluar en fase posterior**:
- ⏳ **Services**: Para lógica compleja (OpenAI workflows)
- ⏳ **Custom Libraries**: OpenAIClient wrapper
- ⏳ **MVC Console Tool**: Si el equipo necesita más scaffolding

---

**Referencias**:
- `doc_proyecto/concepto-de-proyecto.md`
- `doc_consolidada/arquitectura-mvc-consolidada.md`
- `doc_consolidada/modulos-leaf-consolidada.md`
- `SETUP_COMPLETE.md`
- `DATABASE.md`
- `ANALYSIS_AND_ADOPTION_PLAN.md`
