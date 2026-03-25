# Concepto de Proyecto

**Fecha**: 2026-03-24
**Estado**: Borrador inicial
**Referencia**: doc:_respuestas-leaf-docs-researcher/analisis-requisitos-proyecto-inmobiliario.md

---

## Índice de Apartados

1. [Visión General del Proyecto](#1-visión-general-del-proyecto)
   - [1.1. Interfaz de Usuario: Gentelella](#11-interfaz-de-usuario-gentelella)
2. [Bases Funcionales](#2-bases-funcionales)
3. [Conceptualización por MVC](#3-conceptualización-por-mvc)
4. [Arquitectura](#4-arquitectura)
   - [4.1. Sistema de Autenticación: Sesiones + RBAC](#41-sistema-de-autenticación-sesiones--rbac)
5. [Módulos](#5-módulos)
   - [5.1. Sistema de Activación de Funcionalidades (Feature Toggle)](#51-sistema-de-activación-de-funcionalidades-feature-toggle)
6. [Sistema de Configuración](#6-sistema-de-configuración)
   - [6.1. Variables de Entorno](#61-variables-de-entorno)
   - [6.2. Despliegue en Railway](#62-despliegue-en-railway)
   - [6.3. Manejo de Errores](#63-manejo-de-errores)
   - [6.4. Inyección de Dependencias](#64-inyección-de-dependencias)
   - [6.5. Docker](#65-docker)
   - [6.6. Testing](#66-testing)
   - [6.7. Linting](#67-linting)
7. [Supuestos, Dudas y Decisiones Pendientes](#7-supuestos-dudas-y-decisiones-pendientes)
8. [Routing en Leaf PHP](#8-routing-en-leaf-php)
   - [8.1. Route Groups](#81-route-groups)
   - [8.2. Resource Routes](#82-resource-routes)
   - [8.3. Recursos Core Identificados](#83-recursos-core-identificados)
9. [Integración con Middleware de Autenticación](#9-integración-con-middleware-de-autenticación)
10. [Integración con Feature Toggle System](#10-integración-con-feature-toggle-system)
11. [Patrones de Request-Response HTTP](#11-patrones-de-request-response-http)
    - [11.1. Formatos de Respuesta (R6)](#111-formatos-de-respuesta-r6)
    - [11.2. Headers HTTP](#112-headers-http)
    - [11.3. Configuración CORS (R7)](#113-configuración-cors-r7)
    - [11.4. Patrones de Request Handling](#114-patrones-de-request-handling)
    - [11.5. Manejo de Errores HTTP](#115-manejo-de-errores-http)

---

## 1. Visión General del Proyecto

### Propósito del Proyecto

El proyecto es una aplicación web para la gestión y análisis de inmuebles mediante el uso de inteligencia artificial (OpenAI API).

### Problema que Resuelve

Permite a los usuarios crear proyectos de inmuebles, ejecutar análisis automatizados sobre ellos a través de workflows de OpenAI API, y gestionar los resultados e informes generados.

### Alcance General

El proyecto se desarrolla en dos fases:

**Fase MVP (inicial)**:
- Gestión de proyectos (proyecto = inmueble)
- Creación de proyectos con información en formato JSON
- Ejecución de análisis de inmueble mediante OpenAI API (workflow de 9 pasos)
- Almacenamiento de informes de resultados en carpetas de proyecto
- Interfaz de usuario para editar proyectos y visualizar informes

**Fase posterior (no inicial)**:
- Nuevas capacidades de análisis
- Sistema CRM
- Integración con servicios web externos
- Workflows adicionales

### Tipo de Usuario o Contexto de Uso

**Confirmado**: El proyecto es una web-app con interfaz de usuario basada en Gentelella (Bootstrap 5 Admin Dashboard Template).

**No confirmado**: Perfiles específicos de usuario, roles, o contexto organizacional.

---

## 1.1. Interfaz de Usuario: Gentelella

### Confirmación de Selección

**Decisión confirmada**: Gentelella (Bootstrap 5 Admin Dashboard Template) como UI completa para el proyecto.

### Características de Gentelella

**Framework UI**: Bootstrap 5
**Tipo de arquitectura**: Server-side rendering
**Integración con Leaf**: Directa con Blade (motor de vistas recomendado para Leaf)
**Arquitectura del proyecto**: Monolítica (backend + frontend juntos)

### Ventajas de Gentelella para el Proyecto

**Ventajas confirmadas desde documentación de Leaf**:
- Blade es el motor de vistas recomendado para Leaf según [`ANALYSIS_AND_ADOPTION_PLAN.md`](../ANALYSIS_AND_ADOPTION_PLAN.md:384)
- Integración directa y simple con Leaf
- No requiere configuración adicional compleja
- El scaffold actual ya está preparado para server-rendered views

**Ventajas generales**:
- Bootstrap 5 es estable y bien documentado
- Amplia comunidad y recursos disponibles
- No requiere conocimientos de React/Next.js
- Más simple para equipos con experiencia en PHP tradicional

### Adecuación para el Proyecto

**Para MVP**:
- ✅ Adecuado si el equipo tiene experiencia en PHP/Blade
- ✅ Más rápido de implementar
- ✅ Menor complejidad arquitectónica
- ✅ Compatible con autenticación por sesiones
- ✅ Ideal para dashboard de administración simple

**Para Railway**:
- ✅ Despliegue simple (un solo proyecto)
- ✅ Menor complejidad de infraestructura
- ✅ Menor costo potencial (un solo servicio)

### Limitaciones Conocidas

**Limitaciones generales**:
- Bootstrap 5 puede parecer menos moderno que alternativas como Tailwind
- Menor flexibilidad de personalización que Tailwind
- Server-side rendering puede ser más lento en interacciones complejas
- Menor experiencia de usuario en comparación con SPAs

**Cuándo considerar alternativas**:
- Cuando la UX de Gentelella sea insuficiente
- Cuando se necesiten interacciones muy complejas
- Cuando se plane agregar SPA o móvil en el futuro
- Cuando el equipo tenga experiencia en React/Tailwind

### Estrategia de Futuro

**Opción A - Mantener Gentelella**:
- Continuar con Gentelella para todo el proyecto
- Migrar componentes específicos a React cuando sea necesario
- Usar Inertia.js para integración gradual

**Opción B - Migración Completa**:
- Desarrollar MVP con Gentelella
- Una vez validado el concepto, migrar a React + Tailwind
- Usar Inertia.js para simplificar la migración

**Recomendación actual**: Mantener Gentelella para MVP, evaluar migración cuando sea necesario.

---

## 2. Bases Funcionales

### Necesidades que Cubre el Sistema

- Gestión de proyectos de inmuebles
- Análisis automatizado de inmuebles mediante OpenAI API
- Almacenamiento y visualización de informes de análisis
- Edición de información de proyectos existentes

### Tipo de Operativa que se Espera

El usuario interactúa con el sistema para:
1. Crear nuevos proyectos de inmuebles
2. Proporcionar información del inmueble en formato JSON
3. Solicitar la ejecución de análisis (workflow de 9 pasos con OpenAI API)
4. Consultar y visualizar los informes resultantes
5. Editar información de proyectos existentes

### Capacidades Principales que Debería Contemplar el Proyecto

**Confirmadas**:
- Gestión de proyectos (CRUD)
- Ejecución de workflows de análisis con OpenAI API
- Almacenamiento de informes en sistema de archivos
- Interfaz de usuario basada en Gentelella
- Autenticación de usuarios

**No confirmadas**:
- Roles y permisos específicos
- Historial de cambios en proyectos
- Notificaciones o alertas
- Exportación de informes en diferentes formatos

---

## 3. Conceptualización por MVC

### Models

**Papel conceptual**: Manejan los datos y la lógica de negocio relacionada con los proyectos, usuarios, y cualquier otra entidad del dominio.

**Referencia conceptual**: `app/Models/`

**Entidades conocidas**:
- Proyectos (inmuebles)
- Usuarios (para autenticación)

**Responsabilidades conceptuales**:
- Definir la estructura de datos de proyectos y usuarios
- Contener la lógica de negocio para operaciones sobre proyectos
- Interactuar con la base de datos (PostgreSQL)

**No confirmado**: Estructura específica de modelos, relaciones entre entidades, métodos concretos.

---

### Views

**Papel conceptual**: Representan las plantillas visuales y la interacción de presentación con el usuario.

**Referencia conceptual**: `views/`

**Características conocidas**:
- Basadas en Gentelella (Bootstrap 5 Admin Dashboard Template)
- Incluyen vistas para:
  - Edición de proyectos
  - Visualización de informes

**No confirmado**: Estructura específica de vistas, componentes individuales, rutas de navegación.

---

### Controllers

**Papel conceptual**: Conectan los modelos y las vistas, organizan la lógica de flujo y coordinan las operaciones del sistema.

**Referencia conceptual**: `app/Controllers/`

**Responsabilidades conceptuales**:
- Recibir solicitudes del usuario
- Coordinar la ejecución de workflows de OpenAI API
- Gestionar la interacción entre modelos y vistas
- Controlar el flujo de la aplicación

**No confirmado**: Controladores específicos, métodos concretos, rutas definidas.

---

## 4. Arquitectura

### Arquitectura Conceptual de Alto Nivel

El proyecto sigue una arquitectura MVC (Model-View-Controller) típica de aplicaciones web, con las siguientes características:

**Confirmadas**:
- Framework: Leaf PHP
- Base de datos: PostgreSQL
- Interfaz de usuario: Gentelella (Bootstrap 5)
- Autenticación: **Sesiones** (decisión confirmada para MVP)
- Despliegue objetivo: Railway

**Hipótesis marcada**:
- El workflow de 9 pasos de OpenAI API podría ejecutarse de forma síncrona en MVP, o asíncrona mediante sistema de colas si el tiempo de ejecución lo requiere

**No confirmado**:
- Patrón arquitectónico específico más allá de MVC
- Estrategia de comunicación con OpenAI API
- Organización de capas de negocio o servicios

---

## 4.1. Sistema de Autenticación: Sesiones + RBAC

### Confirmación de Selección

**Decisión confirmada**: Sesiones como sistema de autenticación para MVP.

### Características de Sesiones en Leaf

**Tipo de arquitectura**: Stateful (el servidor mantiene el estado de las sesiones)
**Almacenamiento**: Servidor (archivos, base de datos, o Redis)
**Integración con Leaf**: Blade y Leaf tienen soporte nativo para sesiones
**Arquitectura del proyecto**: Monolítica (backend + frontend juntos)

### Ventajas de Sesiones para el Proyecto

**Ventajas confirmadas desde documentación de Leaf**:
- Blade es el motor de vistas recomendado para Leaf
- Integración directa y simple con Leaf
- El scaffold actual ya tiene tabla de `sessions` creada
- No requiere configuración adicional compleja

**Ventajas generales**:
- Mayor simplicidad y velocidad de desarrollo
- Invalidación fácil de sesiones (puedes invalidar en cualquier momento)
- Ideal para web tradicional con Gentelella
- Compatible con RBAC básico

### Adecuación para el Proyecto

**Para MVP**:
- ✅ Adecuado para Gentelella (web tradicional)
- ✅ Más rápido de implementar
- ✅ Menor complejidad arquitectónica
- ✅ Ideal para dashboard de administración simple

**Para Railway**:
- ✅ Despliegue simple (un solo proyecto)
- ✅ Menor complejidad de infraestructura inicial
- ⚠️ Para escalar, requiere almacenamiento compartido (Redis o base de datos)

### Responsabilidades de Leaf vs Desarrollador

**Responsabilidades que asume Leaf**:
- Estructura de middleware (AuthMiddleware)
- Modelo de usuario base (User.php con hash/verify)
- Tabla de sesiones (migración create_sessions_table ya existe)
- Estructura de rutas (permite aplicar middleware)

**Responsabilidades que recaen en el desarrollador**:
- Implementar lógica de login (validar credenciales, crear sesión)
- Implementar lógica de logout (destruir sesión)
- Implementar obtención del usuario autenticado (leer sesión, consultar usuario)
- Implementar protección de rutas (verificar sesión en middleware)

### Relación entre Sesiones, Protección de Rutas y Auth User

**Flujo conceptual**:
1. **User Login**: Usuario envía credenciales → Validar → Crear sesión → Redirigir a dashboard
2. **Protección de rutas**: Solicitud → AuthMiddleware → Verificar sesión → Continuar o redirigir a login
3. **Auth User**: AuthMiddleware → Leer sesión → Extraer user_id → Consultar usuario → Guardar en contexto → Controller usa user()

### Implicaciones de Añadir RBAC sobre Sesiones

**Estructura de datos necesaria**:
- `users`: Usuarios del sistema
- `roles`: Roles (ej: admin, editor, viewer)
- `permissions`: Permisos (ej: create_project, edit_project, delete_project)
- `role_user`: Relación muchos-a-muchos entre usuarios y roles
- `permission_role`: Relación muchos-a-muchos entre roles y permisos
- `sessions`: Sesiones de usuarios (ya existe)

**Integración con sesiones**:
- Al hacer login: Crear sesión con user_id, roles y permisos
- Al validar permisos: Leer permisos de la sesión
- Al cambiar roles/permisos: Invalidar sesión del usuario

**Ventajas de RBAC con sesiones**:
- Centralización de permisos (definidos en un solo lugar)
- Escalabilidad (fácil agregar nuevos roles y permisos)
- Flexibilidad (cambiar permisos de un rol afecta a todos los usuarios con ese rol)
- Invalidación fácil (puedes invalidar sesión cuando cambian roles/permisos)

**Desventajas de RBAC con sesiones**:
- Complejidad adicional (requiere estructura de datos adicional)
- Stateful (las sesiones requieren almacenamiento compartido para escalar)
- Sobrecarga potencial (validar permisos en cada solicitud)

### Limitaciones y Consideraciones Importantes

**Limitaciones técnicas**:
- Almacenamiento de sesiones requiere configuración (archivos, base de datos, o Redis)
- Para escalar en Railway, necesitas almacenamiento compartido (Redis recomendado)
- Las sesiones tienen una expiración fija

**Consideraciones de seguridad**:
- Vulnerable a CSRF (requiere tokens CSRF para rutas que modifican datos)
- Las sesiones pueden ser secuestradas si no se protegen adecuadamente
- `leafs/anchor` puede ayudar con protección CSRF

**Consideraciones de rendimiento**:
- Cada solicitud requiere acceso a almacenamiento de sesiones
- Consultar roles y permisos en cada login puede ser costoso
- Recomendación: Almacenar roles y permisos en la sesión para evitar consultas repetidas

### Estrategia de Futuro

**Cuándo considerar migración a JWT**:
- Cuando agregues API separada
- Cuando agregues aplicación móvil
- Cuando necesites escalar significativamente en Railway
- Cuando la invalidación de sesiones sea un problema

**Estrategia híbrida (opción intermedia)**:
- Mantener sesiones para Gentelella
- Agregar JWT para API/móvil
- Usar Inertia.js para integración gradual

---

## 5. Módulos

### Módulos Leaf Confirmados o Previstos

**Módulos recomendados para MVP** (según análisis de requisitos):

- `leafs/leaf` - Framework core
- `leafs/http` - HTTP layer
- `leafs/anchor` - Seguridad básica
- `leafs/exception` - Manejo de excepciones
- `leafs/db` - Capa de base de datos (PostgreSQL)
- `leafs/mvc` - Wrapper MVC
- Motor de vistas - Blade (motor recomendado para Leaf, para Gentelella)
- Autenticación - Sesiones (decisión confirmada para MVP)
- RBAC - Sistema de roles y permisos básico (decisión confirmada para MVP)

### Módulos de Terceros Confirmados o Previstos

- Gentelella - Bootstrap 5 Admin Dashboard Template (UI)
- OpenAI API - Servicio externo para análisis de inmuebles

### Módulos Pendientes de Validación

**Estado**: No confirmado si Leaf tiene módulos nativos de:
- Workflows
- Colas/jobs (queues)

**Alternativas identificadas**:
- Implementar sistema de colas propio usando Redis + worker
- Usar librería de PHP para colas (ej: enqueue, rabbitmq-bundle)

### Módulos No Incluidos en MVP

- Caché - Se evalúa incluir en fase posterior, no en MVP

---

## 5.1. Sistema de Activación de Funcionalidades (Feature Toggle)

### Concepto General

El proyecto incorporará un sistema de activación de funcionalidades (feature toggle) que permite activar o desactivar módulos y submódulos de la aplicación.

**Características principales**:
- **Configuración persistente**: Tablas de dominio/valor para almacenar estado de activación
- **Activación de módulos completos**: Controla si un módulo funcional está activo (ej: autenticación)
- **Activación de submódulos**: Controla funcionalidades específicas dentro de cada módulo (ej: login, registro, gestión de usuarios, RBAC)
- **Control de acceso por roles**: Define qué roles tienen acceso a cada módulo y submódulo

### Diferencia Importante: Funcionalidades de Aplicación vs Módulos de Leaf

**Funcionalidades de la aplicación**: Lógica de negocio específica del proyecto (gestión de proyectos, análisis OpenAI, informes, CRM, etc.)

**Módulos de Leaf**: Componentes del framework instalados vía Composer (leafs/leaf, leafs/http, leafs/db, etc.)

**Implicación**: El sistema de feature toggle controla EXCLUSIVAMENTE las funcionalidades de la aplicación, no los módulos de Leaf. Los módulos de Leaf permanecen disponibles en el código independientemente de si una funcionalidad de la aplicación está activa o no.

**Ejemplo conceptual**:
- Si desactivas el módulo de "gestión de proyectos" de tu aplicación:
  - Las rutas de proyectos no están disponibles
  - Los elementos de menú de proyectos no se muestran
  - Los endpoints de proyectos retornan 404
  - Pero `leafs/db` sigue funcionando normalmente
  - Pero tu aplicación no lo usa para proyectos

### Compatibilidad con la Filosofía de Leaf

**El sistema de feature toggle ENCAJA con la filosofía de Leaf**, pero con consideraciones importantes:

| Aspecto | Filosofía de Leaf | Sistema de Feature Toggle | Compatibilidad |
|----------|---------------------|------------------------|---------------|
| **Minimalismo** | Prioriza simplicidad | Añade una capa de configuración | ⚠️ Requiere diseño cuidadoso |
| **No opinativo** | No impone arquitectura | Define una arquitectura de configuración | ⚠️ Puede parecer impuesto |
| **Flexibilidad** | Flexible, permite diferentes enfoques | Flexible, configurable | ✅ Compatible |
| **Convenciones** | Sigue convenciones PHP | Puede seguir convenciones PHP | ✅ Compatible |
| **Separación de responsabilidades** | Lógica de negocio separada del framework | Lógica de activación separada de la lógica de negocio | ✅ Compatible |

**Encaja porque**:
- Leaf es flexible y no impone arquitecturas
- Leaf sigue convenciones PHP, el sistema puede seguir las mismas
- Leaf permite diferentes enfoques de organización
- El sistema puede implementarse como una capa de configuración independiente

**Consideraciones importantes**:
- Leaf prioriza simplicidad, el sistema debe implementarse de forma simple
- El sistema no debe parecer impuesto o sobrecargar la arquitectura
- El sistema debe mantenerse separado de la lógica de negocio principal
- El sistema debe seguir las convenciones de PHP y Leaf

### Mecanismos de Leaf que Ayudan a Implementar este Control

#### 1. Middleware

**Qué ofrece Leaf**:
- Estructura de middleware ([`AuthMiddleware.php`](../app/Middleware/AuthMiddleware.php))
- Capacidad de aplicar middleware a rutas específicas
- Capacidad de encadenar múltiples middleware

**Middleware para verificar si un módulo está activo** (concepto):
```php
class ModuleActiveMiddleware
{
    public function call()
    {
        $moduleName = request()->param('module') ?? request()->segment(1);
        
        // Verificar si el módulo está activo en configuración
        if (!$this->isModuleActive($moduleName)) {
            response()->json(['error' => 'Module not active'], 404);
            exit;
        }
        
        // Continuar con la solicitud
    }
}
```

**Middleware para verificar si un usuario tiene acceso a un módulo** (concepto):
```php
class ModuleAccessMiddleware
{
    public function call()
    {
        $moduleName = request()->param('module') ?? request()->segment(1);
        $user = app()->get('auth.user');
        
        // Verificar si el usuario tiene acceso al módulo
        if (!$this->userHasModuleAccess($user, $moduleName)) {
            response()->json(['error' => 'Forbidden'], 403);
            exit;
        }
        
        // Continuar con la solicitud
    }
}
```

#### 2. Routing

**Qué ofrece Leaf**:
- Sistema de routing flexible
- Capacidad de definir rutas con condiciones
- Capacidad de agrupar rutas

**Rutas condicionales por módulo activo** (concepto):
```php
// Solo definir rutas si el módulo está activo
if ($this->isModuleActive('projects')) {
    app()->get('/projects', [ProjectController::class, 'index']);
    app()->get('/projects/create', [ProjectController::class, 'create']);
}
```

#### 3. Configuración

**Qué ofrece Leaf**:
- Estructura de configuración ([`config/app.php`](../config/app.php))
- Capacidad de cargar configuración desde múltiples fuentes
- Capacidad de acceder a configuración desde cualquier parte de la aplicación

**Cargar configuración de módulos activos** (concepto):
```php
// En config/app.php o bootstrap
$activeModules = $this->loadFeatureToggles();
app()->set('active_modules', $activeModules);
```

### Separación de Responsabilidades

#### Responsabilidades que Apoya Leaf Directamente

**Confirmado desde el proyecto**:
- **Middleware**: Estructura de middleware ([`AuthMiddleware.php`](../app/Middleware/AuthMiddleware.php))
- **Routing**: Sistema de routing flexible
- **Configuración**: Estructura de configuración ([`config/app.php`](../config/app.php))
- **Base de datos**: Capa de base de datos ([`leafs/db`](../SETUP_COMPLETE.md:31))
- **Vistas**: Blade como motor de vistas

#### Responsabilidades que Recaen Completamente en el Desarrollador

**Estructura de datos**:
- Diseñar tablas de feature_toggles (módulos y submódulos)
- Diseñar tablas de role_module_permissions
- Diseñar migraciones para estas tablas

**Modelos**:
- Crear modelo FeatureToggle para acceder a configuración
- Crear modelo RoleModulePermission para acceder a permisos
- Crear modelo Role si no existe

**Middleware específicos**:
- Crear ModuleActiveMiddleware para verificar si un módulo está activo
- Crear ModuleAccessMiddleware para verificar si un usuario tiene acceso
- Integrar estos middleware con el sistema de autenticación existente

**Configuración**:
- Cargar configuración de feature toggles al inicio
- Crear helpers o servicios para acceder a la configuración
- Definir estructura de configuración de módulos

### Relación con el Manejo de Autenticación (Sesiones)

#### Separación Clara de Responsabilidades

**Sistema de autenticación de Leaf**:
- Manejo de sesiones (crear, destruir, leer)
- Verificación de si hay sesión activa
- Obtención del usuario autenticado

**Sistema de feature toggle de la aplicación**:
- Verificación de si un módulo está activo
- Verificación de si un usuario tiene acceso a un módulo
- Control de qué elementos de UI se muestran
- Control de qué rutas están disponibles

#### Flujo Conceptual Integrado

**1. Login del usuario**:
```
Usuario → Leaf Auth → Verificar credenciales → Crear sesión
→ Cargar módulos accesibles por rol → Guardar en sesión
→ Redirigir a dashboard
```

**2. Solicitud a una ruta**:
```
Solicitud → AuthMiddleware → Verificar sesión
→ ModuleActiveMiddleware → Verificar módulo activo
→ ModuleAccessMiddleware → Verificar acceso
→ Controller → Procesar solicitud
```

**3. Renderizado de vista**:
```
Controller → Vista Blade
→ Leer módulos activos desde configuración
→ Leer módulos accesibles desde sesión
→ Renderizar elementos condicionales
```

### Consideraciones Conceptuales para un Diseño Limpio

#### Minimalismo

**Filosofía de Leaf**: Prioriza simplicidad, solo incluye lo esencial.

**Cómo aplicar al sistema de feature toggle**:
- Implementar solo lo esencial para el MVP
- No sobre-ingenierar el sistema
- Mantener la estructura simple y mantenible
- Usar las capacidades nativas de Leaf tanto como sea posible

**Ejemplo**:
- Para MVP: Solo verificar si un módulo está activo
- No implementar submódulos complejos en MVP
- No implementar CRUD de gestión en MVP

#### Separación de Responsabilidades

**Filosofía de Leaf**: Lógica de negocio separada del framework.

**Cómo aplicar al sistema de feature toggle**:
- Mantener el sistema de feature toggle separado de la lógica de negocio principal
- No mezclar la lógica de activación con la lógica de negocio de los módulos
- Usar middleware para separar la lógica de activación de la lógica de negocio

**Ejemplo**:
- La lógica de gestión de proyectos no debe saber si el módulo está activo
- El middleware verifica si el módulo está activo antes de llegar al controller
- El controller solo se preocupa por la lógica de negocio de proyectos

#### Convenciones

**Filosofía de Leaf**: Sigue convenciones de PHP (PSR-4, PSR-12, etc.)

**Cómo aplicar al sistema de feature toggle**:
- Seguir convenciones de nomenclatura para tablas y modelos
- Seguir convenciones de PHP para namespaces y clases
- Usar la estructura de configuración de Leaf

**Ejemplo**:
- Nombres de tablas: snake_case (feature_toggles, role_module_permissions)
- Nombres de clases: PascalCase (FeatureToggle, RoleModulePermission)
- Namespaces: App\Models, App\Middleware

### Arquitectura Recomendada para el Sistema de Feature Toggle

#### Estructura de Datos

**Tablas principales** (concepto):

```sql
-- Tabla de feature toggles (módulos y submódulos)
CREATE TABLE feature_toggles (
    id SERIAL PRIMARY KEY,
    module VARCHAR(255) NOT NULL,
    submodule VARCHAR(255),
    is_active BOOLEAN DEFAULT true,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(module, submodule)
);

-- Tabla de roles
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de permisos de roles sobre módulos
CREATE TABLE role_module_permissions (
    id SERIAL PRIMARY KEY,
    role_id INTEGER NOT NULL,
    module VARCHAR(255) NOT NULL,
    submodule VARCHAR(255),
    can_access BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE(role_id, module, submodule)
);
```

#### Estructura de Código

**Modelos**:
- `FeatureToggle`: Para acceder a la configuración de feature toggles
- `RoleModulePermission`: Para acceder a los permisos de roles
- `Role`: Para gestionar roles (si no existe)

**Middleware**:
- `ModuleActiveMiddleware`: Para verificar si un módulo está activo
- `ModuleAccessMiddleware`: Para verificar si un usuario tiene acceso a un módulo

**Integración**:
- Integrar con `AuthMiddleware` existente
- Cargar configuración al inicio de la aplicación
- Aplicar middleware a rutas específicas

### Recomendaciones Específicas para MVP

**Recomendación**: Implementar sistema de feature toggle simple y progresivo.

**Fase 1 - Estructura base**:
- Crear tablas de feature_toggles y role_module_permissions
- Crear modelos base (FeatureToggle, RoleModulePermission)
- Crear roles iniciales (usuario, administrador)
- Crear configuración inicial para módulos

**Fase 2 - Middleware**:
- Crear ModuleActiveMiddleware para verificar si un módulo está activo
- Crear ModuleAccessMiddleware para verificar si un usuario tiene acceso
- Integrar estos middleware con el sistema de autenticación existente

**Fase 3 - Integración con rutas**:
- Aplicar middleware a rutas específicas
- Verificar que el sistema funciona correctamente

**Fase 4 - Integración con vistas**:
- Implementar elementos de menú condicionales
- Implementar submódulos condicionales
- Verificar que la UI se actualiza correctamente

**Fase 5 - Pruebas**:
- Probar activación/desactivación de módulos
- Probar control de acceso por roles
- Verificar que el sistema no añade fricción innecesaria

### Referencias Detalladas

Para más información sobre cómo este sistema encaja con Leaf, ver:
- [`doc_consolidada/evaluacion-feature-toggle-funcionalidades-app-leaf.md`](../doc_consolidada/evaluacion-feature-toggle-funcionalidades-app-leaf.md)
- [`doc_consolidada/feature-toggle-consolidada.md`](../doc_consolidada/feature-toggle-consolidada.md)

### Validaciones Realizadas

| Validación | Estado | Notas |
|------------|--------|-------|
| Estructura de datos compatible con Leaf | ✅ Validada | Sigue convenciones PHP/Leaf (snake_case, PascalCase) |
| Índices para rendimiento | ✅ Validada | Índices en module, is_active y role_id |
| Migraciones numeradas | ✅ Validada | Sigue R9 del proyecto |
| Datos iniciales documentados | ✅ Validada | Ejemplo para módulo de proyectos |
| Integración con routing | ✅ Validada | Compatible con sistema de routing de Leaf |
| Integración con middleware | ✅ Validada | Orden correcto: Auth → Active → Access |
| Compatibilidad con RBAC | ✅ Validada | Compatible con sistema de autenticación y roles |
| Separación de responsabilidades | ✅ Validada | Lógica de activación separada de lógica de negocio |
| Filosofía de Leaf | ✅ Validada | Minimalismo, flexibilidad, convenciones PHP |

### Vacíos o Decisiones Pendientes

| Decisión | Impacto | Recomendación |
|----------|---------|---------------|
| Estrategia de caché para feature toggles | Rendimiento | Implementar caché simple en MVP, evaluar caché más sofisticado en fase posterior |
| Nivel de granularidad de feature toggles (módulos vs submódulos) | Flexibilidad | Implementar ambos niveles desde inicio para máxima flexibilidad |
| Cuándo incluir CRUD de gestión de feature toggles | Administración | Incluir en fase posterior, no en MVP |
| Invalidación de sesiones al cambiar permisos de roles | Seguridad | Invalidar sesiones automáticamente al actualizar permisos |
| Interfaz de administración para gestión del sistema | UX | Incluir en fase posterior, no en MVP |

### Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Sistema de Feature Toggle para funcionalidades | concepto-de-proyecto.md, rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |
| Distinción: funcionalidades vs módulos Leaf | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Compatibilidad con filosofía de Leaf | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Middleware para verificar módulo activo | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Rutas condicionales por módulo | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Configuración de módulos activos | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Estructura de datos (feature_toggles, roles, role_module_permissions) | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Integración con AuthMiddleware | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Orden de middleware (Auth → Active → Access) | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |

### Clasificación de la Información

| Tipo | Contenido |
|------|-----------|
| **Hecho documentado** | AuthMiddleware.php existe; config/app.php existe; leafs/db existe; Blade es motor de vistas recomendado |
| **Observación del proyecto** | El scaffold tiene estructura de middleware, configuración y base de datos |
| **Información no verificada** | Funcionalidades específicas de Leaf para configuración dinámica |
| **Conocimiento general** | Diseño de sistemas de feature toggle; integración con sesiones; consideraciones arquitectónicas |
| **Decisión arquitectónica** | Sistema de feature toggle para funcionalidades de aplicación |
| **Decisión de diseño** | Estructura de datos con tablas feature_toggles, roles y role_module_permissions |

---

## 6. Sistema de Configuración

### 6.1. Variables de Entorno

**Decisión Confirmada**: Uso de variables de entorno para toda la configuración sensible y valores que pueden variar entre entornos.

#### Variables Requeridas

| Variable | Entorno | Sensible | Propósito |
|----------|---------|----------|-----------|
| `APP_ENV` | dev/prod | No | Entorno de la aplicación |
| `APP_DEBUG` | dev/prod | No | Modo de depuración |
| `APP_NAME` | dev/prod | No | Nombre de la aplicación |
| `APP_URL` | dev/prod | No | URL base de la aplicación |
| `APP_TIMEZONE` | dev/prod | No | Zona horaria |
| `APP_LOCALE` | dev/prod | No | Idioma por defecto |
| `APP_KEY` | dev/prod | **Sí** | Clave de encriptación |
| `DB_CONNECTION` | dev/prod | No | Tipo de base de datos |
| `DB_HOST` | dev/prod | No | Host de base de datos |
| `DB_PORT` | dev/prod | No | Puerto de base de datos |
| `DB_DATABASE` | dev/prod | No | Nombre de base de datos |
| `DB_USER` | dev/prod | No | Usuario de base de datos |
| `DB_PASSWORD` | dev/prod | **Sí** | Contraseña de base de datos |
| `SESSION_DRIVER` | dev/prod | No | Driver de sesiones |
| `SESSION_LIFETIME` | dev/prod | No | Duración de sesiones (minutos) |
| `SESSION_NAME` | dev/prod | No | Nombre de cookie de sesión |
| `SESSION_PATH` | dev/prod | No | Ruta de almacenamiento de sesiones |
| `LOG_LEVEL` | dev/prod | No | Nivel de logging |
| `LOG_PATH` | dev/prod | No | Ruta de almacenamiento de logs |
| `CORS_ORIGIN` | dev/prod | No | Orígenes permitidos para CORS |

#### Cumplimiento de R2 (Cero Hardcoding)

- ✅ Todos los valores configurables usan variables de entorno
- ✅ No hay literales de credenciales o URLs en el código
- ✅ Valores por defecto proporcionados para desarrollo

#### Cumplimiento de R3 (Gestión de Secrets)

- ✅ Secrets accedidos vía variables de entorno
- ✅ Archivo `.env.example` como plantilla (versionado)
- ✅ Archivo `.env` no versionado (existe en `.gitignore`)
- ✅ Secrets de Railway configurados como variables de entorno

---

### 6.2. Despliegue en Railway

**Decisión Confirmada**: Railway como plataforma de despliegue para el proyecto.

#### Configuración de Railway

**Archivo `railway.json`**:
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

#### Variables de Entorno en Railway

| Variable | Propósito | Ejemplo | Sensible |
|----------|-----------|---------|----------|
| `APP_ENV` | Entorno de la aplicación | `production` | No |
| `APP_DEBUG` | Modo de depuración | `false` | No |
| `APP_NAME` | Nombre de la aplicación | `Hoja App` | No |
| `APP_URL` | URL de la aplicación | `https://hoja.railway.app` | No |
| `APP_TIMEZONE` | Zona horaria | `Europe/Madrid` | No |
| `APP_LOCALE` | Idioma por defecto | `es-ES` | No |
| `APP_KEY` | Clave de encriptación | `[generado]` | **Sí** |
| `DB_HOST` | Host de PostgreSQL | `postgres.railway.app` | No |
| `DB_PORT` | Puerto de PostgreSQL | `5432` | No |
| `DB_DATABASE` | Nombre de base de datos | `railway` | No |
| `DB_USER` | Usuario de PostgreSQL | `postgres` | No |
| `DB_PASSWORD` | Contraseña de PostgreSQL | `[generado]` | **Sí** |
| `SESSION_DRIVER` | Driver de sesiones | `database` | No |
| `SESSION_LIFETIME` | Duración de sesiones | `120` | No |
| `LOG_LEVEL` | Nivel de logging | `error` | No |
| `CORS_ORIGIN` | Orígenes permitidos | `https://hoja.railway.app` | No |

#### Cumplimiento de R8 (Configuración de Despliegue)

- ✅ Configuración explícita en `railway.json`
- ✅ Variables de entorno sensibles configuradas en Railway
- ✅ Método de despliegue documentado (Railway)

#### Consideraciones de Escalabilidad

**Sesiones en Railway**:
- Railway escala horizontalmente automáticamente
- Para escalar con sesiones, se requiere almacenamiento compartido:
  - **Opción 1**: Redis (recomendado para producción)
  - **Opción 2**: Base de datos (ya configurado)
  - **Opción 3**: Sticky sessions (reduce eficiencia del balanceador de carga)

**Para MVP**:
- Almacenamiento de sesiones en base de datos es suficiente
- No se requiere Redis inicialmente

**Para Producción (escalado)**:
- Evaluar Redis si el tráfico aumenta significativamente
- Redis proporciona mejor rendimiento para sesiones

---

### 6.3. Manejo de Errores

**Decisión Confirmada**: Estrategia de logging basada en archivos, con diferenciación por entorno.

#### Estrategia de Logging

**Configuración de variables de entorno**:
- `LOG_DRIVER`: `file` (almacenamiento en archivos)
- `LOG_LEVEL`: Nivel de logging (`debug`, `info`, `warning`, `error`)
- `LOG_PATH`: Ruta de almacenamiento de logs (`./storage/logs`)
- `LOG_FILE`: Nombre del archivo de log (`app.log`)

#### Niveles de Logging por Entorno

| Entorno | Nivel de Logging | Propósito |
|----------|------------------|-----------|
| Desarrollo | `debug` | Información detallada para depuración |
| Producción | `error` | Solo errores críticos |

#### Manejo de Errores en Producción (R6)

**Regla de Seguridad**: No exponer stack traces en producción.

```php
// Desarrollo - con información de depuración
if (app()->environment() === 'development') {
    return response()->json([
        'error' => $exception->getMessage(),
        'trace' => $exception->getTraceAsString()
    ], 500);
}

// Producción - sin información sensible
return response()->json([
    'error' => 'Error interno del servidor'
], 500);
```

#### Errores Amigables para Usuario

**Para API (JSON)**:
```php
return response()->json([
    'error' => 'Error interno del servidor'
], 500);
```

**Para Web (HTML)**:
- Vista de error personalizada en `views/errors/500.view.php`
- Mensaje amigable sin información técnica

---

### 6.4. Inyección de Dependencias

**Estado**: No confirmado si Leaf tiene contenedor de DI nativo.

#### Análisis de Opciones

**Opción 1: Contenedor de DI nativo de Leaf**
- Si Leaf tiene contenedor de DI nativo, usarlo
- Registrar servicios en bootstrap de la aplicación

**Opción 2: Implementar contenedor propio**
- Implementar contenedor simple de DI
- Usar patrón Service Locator si es necesario

**Opción 3: Usar librería externa**
- Usar librería de PHP para DI (ej: PHP-DI)
- Evaluar si es necesario para MVP

#### Servicios Potencialmente Registrables

| Servicio | Interfaz | Implementación |
|----------|----------|----------------|
| Database | DatabaseInterface | PostgreSQL |
| Session | SessionInterface | DatabaseSession |
| Cache | CacheInterface | FileCache |
| Logger | LoggerInterface | FileLogger |
| Config | ConfigInterface | ArrayConfig |

#### Decisión Pendiente

- **Validar en documentación oficial de Leaf**: https://leafphp.dev/modules/
- Si Leaf tiene módulo de DI nativo, usarlo
- Si no, evaluar si es necesario para MVP o se puede posponer

---

### 6.5. Docker

**Decisión Confirmada**: Uso de Docker para despliegue en Railway.

#### Dockerfile para Producción

**Características confirmadas**:
- Multi-stage build (optimización de imagen)
- PHP 8.2 CLI
- Extensiones necesarias instaladas
- OPcache configurado para producción
- Usuario no-root para seguridad
- Entrypoint script para migraciones y arranque

#### docker-compose.yml para Desarrollo

**Estado**: Archivo `docker-compose.yml` existe en el proyecto.

**Servicios configurados**:
- **app**: Servicio principal de la aplicación
- **db**: PostgreSQL para base de datos
- **nginx**: Servidor web (opcional)

#### Uso Recomendado

**Desarrollo local**:
- Usar `docker-compose up` para levantar servicios
- Configurar variables de entorno en `.env.local`

**Producción (Railway)**:
- Railway usa el `Dockerfile` para construir la imagen
- Variables de entorno configuradas en Railway

---

### 6.6. Testing

**Estado**: Estrategia de testing pendiente de definir.

#### Framework de Testing

**Opciones identificadas**:
- **PHPUnit**: Framework de testing estándar para PHP
- **Pest**: Framework de testing con sintaxis más moderna
- **Leaf Test**: Módulo de testing de Leaf (si existe)

#### Configuración de Tests

**Requisitos según R10**:
- Configurar mocks y bindings en archivo de configuración del test
- Ejecutar tests como parte del pipeline de CI
- Tests deben ejecutarse en entorno real o emulado

#### Tests Confirmados en el Proyecto

**Archivos existentes en `tests/`**:
- `app.test.php` - Tests de aplicación
- `config.test.php` - Tests de configuración
- `container.test.php` - Tests de contenedor
- `core.test.php` - Tests de core
- `functional.test.php` - Tests funcionales
- `middleware.test.php` - Tests de middleware
- `view.test.php` - Tests de vistas

#### Decisión Pendiente

- **Validar en documentación oficial de Leaf**: ¿Existe módulo de testing nativo?
- **Definir framework de testing**: PHPUnit vs Pest vs Leaf Test
- **Configurar pipeline de CI**: GitHub Actions para ejecutar tests

---

### 6.7. Linting

**Estado**: Configuración de linting pendiente de definir.

#### Herramientas de Linting para PHP

**Opciones identificadas**:
- **PHP CS Fixer**: Formateador de código PHP estándar
- **PHPStan**: Analizador estático de PHP
- **Psalm**: Analizador estático de PHP con detección de bugs
- **Larastan**: Wrapper de PHPStan para Laravel (no aplicable a Leaf)

#### Configuración de Reglas

**Requisitos según R11**:
- Ejecutar linters y typechecks antes de commit
- Resolver o silenciar advertencias relevantes
- El proyecto debe compilarse sin errores

#### Integración en Pre-commit

**Opciones**:
- **Husky**: Git hooks para pre-commit
- **Composer scripts**: Scripts de Composer para linting
- **GitHub Actions**: Linting en CI pipeline

#### Decisión Pendiente

- **Definir herramientas de linting**: PHP CS Fixer + PHPStan
- **Configurar reglas de estilo**: PSR-12 (estándar PHP)
- **Configurar pre-commit hooks**: Husky o similar
- **Configurar CI pipeline**: GitHub Actions para linting

---

### 6.8. Validaciones Realizadas

| Validación | Estado | Notas |
|------------|--------|-------|
| Cumplimiento R2 (no hardcoding) | ✅ Validada | Todos los valores usan env(), no hay hardcoding |
| Cumplimiento R3 (secrets) | ✅ Validada | Secrets accedidos vía env(), no versionados |
| Cumplimiento R8 (despliegue) | ✅ Validada | Configuración explícita en railway.json |
| Cumplimiento R8.b (método explícito) | ✅ Validada | Método de despliegue (Railway) documentado en reglas y agentes |
| Cumplimiento R10 (testing) | ⚠️ Pendiente | Estrategia de tests no definida completamente |
| Cumplimiento R11 (calidad) | ⚠️ Pendiente | Configuración de linting no definida |

---

## 7. Routing en Leaf PHP

### 7.1. Route Groups

**Definición**: Los grupos de rutas permiten agrupar múltiples rutas bajo un prefijo común y aplicar middleware a todo el grupo.

**Características**:
- **Prefijo común**: Todas las rutas del grupo comparten el prefijo (ej: `/api/v1`)
- **Middleware compartido**: El middleware se aplica a todas las rutas del grupo
- **Organización**: Mejor organización de rutas relacionadas
- **Eficiencia**: Menos código duplicado

**Sintaxis**:
```php
app()->group('/api/v1', ['middleware' => 'auth'], function () {
    app()->get('/projects', [ProjectController::class, 'index']);
    app()->post('/projects', [ProjectController::class, 'store']);
    app()->get('/projects/{id}', [ProjectController::class, 'show']);
});
```

**Uso Recomendado para el Proyecto**:
- Agrupar rutas de API bajo `/api/v1`
- Agrupar rutas protegidas con middleware de autenticación
- Agrupar rutas de recursos específicos (ej: `/projects`, `/users`)

**Referencia**: [`doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md`](doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md)

---

### 7.2. Resource Routes

**Definición**: Las rutas de recursos permiten definir automáticamente todas las operaciones CRUD (Create, Read, Update, Delete) para un recurso.

**Características**:
- **Convención RESTful**: Sigue las convenciones RESTful para nombres de métodos
- **Automático**: Crea todas las rutas CRUD automáticamente
- **Eficiencia**: Menos código, más rápido de implementar

**Sintaxis**:
```php
app()->resource('/projects', ProjectController::class);

// Esto crea automáticamente:
// GET    /projects           → index()      (listar todos)
// GET    /projects/create    → create()     (formulario de creación)
// POST   /projects           → store()      (guardar nuevo)
// GET    /projects/{id}      → show($id)    (ver uno)
// PUT/PATCH /projects/{id}   → update($id)  (actualizar)
// DELETE /projects/{id}      → destroy($id) (eliminar)
```

**Uso Recomendado para el Proyecto**:
- **Gestión de proyectos**: Usar resource routes para CRUD completo de proyectos
- **Gestión de usuarios**: Usar resource routes para CRUD completo de usuarios
- **Gestión de informes**: Usar resource routes si los informes son recursos gestionables

**Referencia**: [`doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md`](doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md)

---

### 7.3. Recursos Core Identificados

Según el concepto del proyecto y las necesidades funcionales, se identifican los siguientes recursos core que requieren estructura de routing:

#### Tabla de Recursos Core

| Recurso | Rutas Base | Métodos | Protección | Controller |
|---------|------------|---------|------------|------------|
| **Proyectos** | `/projects` | GET, POST, PUT/PATCH, DELETE | Auth + Roles (admin, editor) | `ProjectController` |
| **Usuarios** | `/users` | GET, POST, PUT/PATCH, DELETE | Auth + Roles (admin) | `UserController` |
| **Autenticación** | `/auth` | GET, POST | Pública (login, registro) | `AuthController` |
| **Dashboard** | `/` | GET | Auth (cualquier rol) | `HomeController` |
| **Informes** | `/projects/{id}/reports` | GET, POST | Auth + Roles (admin, editor, viewer) | `ReportController` |

#### Descripción Detallada de Recursos

**Proyectos**:
- **Propósito**: Gestión de inmuebles (proyectos)
- **Operaciones CRUD**: Crear, leer, actualizar, eliminar proyectos
- **Formato de datos**: JSON para información del inmueble
- **Relaciones**: Un proyecto puede tener múltiples informes de análisis
- **Protección**: Admin y editor pueden crear/editar/eliminar; viewer solo puede leer

**Usuarios**:
- **Propósito**: Gestión de usuarios del sistema
- **Operaciones CRUD**: Crear, leer, actualizar, eliminar usuarios
- **Autenticación**: Login, logout, registro
- **RBAC**: Gestión de roles y permisos
- **Protección**: Solo admin puede gestionar usuarios

**Autenticación**:
- **Propósito**: Gestionar el flujo de autenticación
- **Operaciones**: Login, logout, registro, recuperación de contraseña
- **Protección**: Rutas públicas (excepto logout que requiere auth)
- **Middleware**: AuthMiddleware para verificar sesión

**Dashboard**:
- **Propósito**: Vista principal de la aplicación
- **Operaciones**: GET para mostrar dashboard
- **Protección**: Requiere autenticación (cualquier rol)
- **Contenido**: Resumen de proyectos, informes recientes, acceso rápido

**Informes**:
- **Propósito**: Almacenamiento y visualización de informes de análisis
- **Operaciones**: GET para visualizar, POST para generar nuevo análisis
- **Relaciones**: Pertenecen a un proyecto específico
- **Protección**: Admin y editor pueden generar informes; viewer solo puede visualizar

#### Grupos de Rutas Propuestos

**Grupo de Autenticación**:
- **Prefijo**: `/auth`
- **Middleware**: Ninguno (rutas públicas)
- **Rutas**:
  - `GET /auth/login` - Formulario de login
  - `POST /auth/login` - Procesar login
  - `GET /auth/register` - Formulario de registro
  - `POST /auth/register` - Procesar registro
  - `POST /auth/logout` - Procesar logout (requiere auth)

**Grupo de API**:
- **Prefijo**: `/api/v1`
- **Middleware**: AuthMiddleware + CORS
- **Rutas**:
  - `GET /api/v1/projects` - Listar proyectos
  - `POST /api/v1/projects` - Crear proyecto
  - `GET /api/v1/projects/{id}` - Ver proyecto
  - `PUT/PATCH /api/v1/projects/{id}` - Actualizar proyecto
  - `DELETE /api/v1/projects/{id}` - Eliminar proyecto
  - `GET /api/v1/projects/{id}/reports` - Listar informes
  - `POST /api/v1/projects/{id}/reports` - Generar informe

**Grupo de Administración**:
- **Prefijo**: `/admin`
- **Middleware**: AuthMiddleware + RoleAuthMiddleware(['admin'])
- **Rutas**:
  - `GET /admin/users` - Listar usuarios
  - `POST /admin/users` - Crear usuario
  - `GET /admin/users/{id}` - Ver usuario
  - `PUT/PATCH /admin/users/{id}` - Actualizar usuario
  - `DELETE /admin/users/{id}` - Eliminar usuario

#### Recursos Excluidos (Feature Toggle)

| Recurso | Razón | Agente Responsable |
|---------|-------|-------------------|
| **CRM** | Funcionalidad de fase posterior | `agt-feature-toggle-designer` |
| **Workflows adicionales** | Funcionalidad de fase posterior | `agt-feature-toggle-designer` |
| **Integración servicios externos** | Funcionalidad de fase posterior | `agt-feature-toggle-designer` |

**Referencia**: El diseño de routing para funcionalidades condicionales por módulo es responsabilidad de [`agt-feature-toggle-designer`](../.claude/agents/ejecutores/agt-feature-toggle-designer.md).

---

## 8. Integración con Middleware de Autenticación

### Flujo Completo de Autenticación en Routing

```
1. Solicitud HTTP
2. router.before hook
3. AuthMiddleware → Verifica sesión
4. Router encuentra ruta
5. router.before.route hook
6. AuthMiddleware → Verifica sesión (si es ruta protegida)
7. Controller → Ejecuta lógica
8. router.after.route hook
9. AuthMiddleware → Carga usuario en contexto
10. router.after hook
11. Respuesta enviada al usuario
```

### Implementación Conceptual

**Middleware de autenticación**:
```php
class AuthMiddleware
{
    public function call()
    {
        // Verificar si hay sesión activa
        if (!$this->hasSession()) {
            response()->json(['error' => 'Unauthorized'], 401);
            exit;
        }

        // Cargar usuario autenticado
        $user = $this->loadUserFromSession();

        // Guardar usuario en contexto para usar en controllers
        app()->set('auth.user', $user);
    }
}
```

### Aplicación a Rutas Protegidas

**Ruta protegida individual**:
```php
app()->get('/projects/create', [ProjectController::class, 'create'])
    ->middleware('auth');
```

**Grupo protegido**:
```php
app()->group('/api/v1', ['middleware' => 'auth'], function () {
    app()->post('/projects', [ProjectController::class, 'store']);
    app()->get('/projects/{id}', [ProjectController::class, 'show']);
});
```

**Resource route protegido**:
```php
app()->resource('/projects', ProjectController::class)
    ->middleware('auth');
```

### Integración con Sesiones + RBAC

**Middleware combinado (Auth + Roles)**:
```php
class RoleAuthMiddleware
{
    protected $roles = [];
    
    public function __construct(array $roles = [])
    {
        $this->roles = $roles;
    }
    
    public function call()
    {
        // Verificar autenticación
        if (!$this->hasSession()) {
            response()->json(['error' => 'Unauthorized'], 401);
            exit;
        }
        
        // Cargar usuario
        $user = $this->loadUserFromSession();
        app()->set('auth.user', $user);
        
        // Verificar roles si se especificaron
        if (!empty($this->roles)) {
            $userRole = $user['role'] ?? 'guest';
            if (!in_array($userRole, $this->roles)) {
                response()->json(['error' => 'Forbidden'], 403);
                exit;
            }
        }
    }
}
```

**Uso con roles específicos**:
```php
// Solo administradores pueden gestionar usuarios
app()->group('/admin/users', ['middleware' => new RoleAuthMiddleware(['admin'])], function () {
    app()->get('/', [Admin\UserController::class, 'index']);
    app()->post('/', [Admin\UserController::class, 'store']);
    app()->get('/{id}', [Admin\UserController::class, 'show']);
});

// Admins y editores pueden gestionar proyectos
app()->resource('/projects', ProjectController::class)
    ->middleware(new RoleAuthMiddleware(['admin', 'editor']));
```

**Referencia**: [`doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md`](doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md)

---

## 9. Integración con Feature Toggle System

### Rutas Condicionales por Módulo

El sistema de feature toggle puede integrarse con el routing de Leaf para activar/desactivar rutas según configuración.

### Middleware para Verificar Actividad de Módulo

**Middleware de verificación de módulo**:
```php
class ModuleActiveMiddleware
{
    protected $moduleName;
    
    public function __construct(string $moduleName)
    {
        $this->moduleName = $moduleName;
    }
    
    public function call()
    {
        // Verificar si el módulo está activo
        if (!$this->featureToggle->isModuleActive($this->moduleName)) {
            if (request()->expectsJson()) {
                response()->json(['error' => 'Module not active'], 404);
            } else {
                response()->redirect('/')->with('error', 'Funcionalidad no disponible');
            }
            exit;
        }
        
        // Continuar con la solicitud
    }
}
```

### Rutas Condicionales por Módulo

**Definición condicional de rutas**:
```php
// Solo definir rutas si el módulo está activo
if ($this->featureToggle->isModuleActive('projects')) {
    app()->get('/projects', [ProjectController::class, 'index']);
    app()->post('/projects', [ProjectController::class, 'store']);
    app()->get('/projects/{id}', [ProjectController::class, 'show']);
}
```

**Grupos condicionales con middleware**:
```php
// Solo activar grupo si el módulo está activo
if ($this->featureToggle->isModuleActive('projects')) {
    app()->group('/projects', ['middleware' => 'auth'], function () {
        app()->get('/', [ProjectController::class, 'index']);
        app()->post('/', [ProjectController::class, 'store']);
        app()->get('/{id}', [ProjectController::class, 'show']);
    });
}
```

**Middleware de verificación aplicado a grupos**:
```php
// Usar middleware para verificar actividad del módulo
app()->group('/projects', [
    'middleware' => [
        'auth',
        new ModuleActiveMiddleware('projects')
    ]
], function () {
    app()->get('/', [ProjectController::class, 'index']);
    app()->post('/', [ProjectController::class, 'store']);
});
```

### Integración con Submódulos

**Middleware para verificar submódulo**:
```php
class SubmoduleActiveMiddleware
{
    protected $moduleName;
    protected $submoduleName;
    
    public function __construct(string $moduleName, string $submoduleName)
    {
        $this->moduleName = $moduleName;
        $this->submoduleName = $submoduleName;
    }
    
    public function call()
    {
        // Verificar si el módulo y submódulo están activos
        if (!$this->featureToggle->isSubmoduleActive($this->moduleName, $this->submoduleName)) {
            if (request()->expectsJson()) {
                response()->json(['error' => 'Submodule not active'], 404);
            } else {
                response()->redirect('/projects')->with('error', 'Funcionalidad no disponible');
            }
            exit;
        }
    }
}
```

**Uso con submódulos específicos**:
```php
// Rutas para creación de proyectos (submódulo 'create' del módulo 'projects')
app()->group('/projects/create', [
    'middleware' => [
        'auth',
        new SubmoduleActiveMiddleware('projects', 'create')
    ]
], function () {
    app()->get('/', [ProjectController::class, 'create']);
    app()->post('/', [ProjectController::class, 'store']);
});

// Rutas para edición de proyectos (submódulo 'edit' del módulo 'projects')
app()->group('/projects/{id}/edit', [
    'middleware' => [
        'auth',
        new SubmoduleActiveMiddleware('projects', 'edit')
    ]
], function () {
    app()->get('/', [ProjectController::class, 'edit']);
    app()->put('/', [ProjectController::class, 'update']);
});
```

### Integración con RBAC y Feature Toggle

**Middleware combinado (Auth + Roles + Feature Toggle)**:
```php
class CompleteAccessMiddleware
{
    protected $roles = [];
    protected $moduleName;
    protected $submoduleName;
    
    public function __construct(array $roles, string $moduleName, string $submoduleName = null)
    {
        $this->roles = $roles;
        $this->moduleName = $moduleName;
        $this->submoduleName = $submoduleName;
    }
    
    public function call()
    {
        // 1. Verificar autenticación
        if (!$this->hasSession()) {
            response()->json(['error' => 'Unauthorized'], 401);
            exit;
        }
        
        // 2. Cargar usuario
        $user = $this->loadUserFromSession();
        app()->set('auth.user', $user);
        
        // 3. Verificar roles
        if (!empty($this->roles)) {
            $userRole = $user['role'] ?? 'guest';
            if (!in_array($userRole, $this->roles)) {
                response()->json(['error' => 'Forbidden'], 403);
                exit;
            }
        }
        
        // 4. Verificar módulo activo
        if (!$this->featureToggle->isModuleActive($this->moduleName)) {
            response()->json(['error' => 'Module not active'], 404);
            exit;
        }
        
        // 5. Verificar submódulo activo (si se especificó)
        if ($this->submoduleName && !$this->featureToggle->isSubmoduleActive($this->moduleName, $this->submoduleName)) {
            response()->json(['error' => 'Submodule not active'], 404);
            exit;
        }
    }
}
```

**Uso completo en el proyecto**:
```php
// Crear proyectos: requiere autenticación, rol admin/editor, módulo projects activo, submódulo create activo
app()->post('/projects', [ProjectController::class, 'store'])
    ->middleware(new CompleteAccessMiddleware(['admin', 'editor'], 'projects', 'create'));

// Ver proyectos: requiere autenticación, cualquier rol, módulo projects activo
app()->get('/projects', [ProjectController::class, 'index'])
    ->middleware(new CompleteAccessMiddleware(['admin', 'editor', 'viewer'], 'projects'));

// Eliminar proyectos: requiere autenticación, rol admin, módulo projects activo, submódulo delete activo
app()->delete('/projects/{id}', [ProjectController::class, 'destroy'])
    ->middleware(new CompleteAccessMiddleware(['admin'], 'projects', 'delete'));
```

**Referencia**: [`doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md`](doc:_respuestas-leaf-docs-researcher/Legado/routing-leaf.md)

---

## 10. Patrones de Request-Response HTTP

### 10.1. Formatos de Respuesta (R6)

**Regla R6 — Convención de respuestas HTTP**: Todas las respuestas HTTP del proyecto deben seguir esta convención.

#### Tabla de Formatos de Respuesta

| Tipo | Estructura | Status | Ejemplo |
|------|------------|--------|---------|
| Éxito con payload | `{ data: ... }` | 2xx | `{ "data": { "id": 1, "name": "Proyecto" } }` |
| Éxito sin payload | `{ message: "..." }` | 200/204 | `{ "message": "Usuario actualizado correctamente" }` |
| Error | `{ error: "..." }` | 4xx/5xx | `{ "error": "Recurso no encontrado" }` |

#### Respuestas de Éxito

**Con Payload (2xx)**

```php
// Obtener un proyecto
return response()->json([
    'data' => [
        'id' => 1,
        'name' => 'Proyecto Inmobiliario',
        'status' => 'active'
    ]
], 200);

// Crear un proyecto
return response()->json([
    'data' => [
        'id' => 1,
        'name' => 'Nuevo Proyecto'
    ]
], 201);
```

**Sin Payload (200/204)**

```php
// Operación exitosa con mensaje
return response()->json([
    'message' => 'Usuario actualizado correctamente'
], 200);

// Operación exitosa sin contenido
return response()->json([], 204);
```

#### Respuestas de Error

**Errores de Cliente (4xx)**

```php
// Bad Request (400) - Datos inválidos
return response()->json([
    'error' => 'Los campos name, email y password son requeridos'
], 400);

// Unauthorized (401) - No autenticado
return response()->json([
    'error' => 'No autorizado. Debe iniciar sesión.'
], 401);

// Forbidden (403) - Sin permisos
return response()->json([
    'error' => 'No tiene permisos para acceder a este recurso'
], 403);

// Not Found (404) - Recurso no existe
return response()->json([
    'error' => 'Recurso no encontrado'
], 404);
```

**Errores de Servidor (5xx)**

```php
// Internal Server Error (500)
return response()->json([
    'error' => 'Error interno del servidor'
], 500);

// Service Unavailable (503)
return response()->json([
    'error' => 'Servicio no disponible'
], 503);
```

#### Regla de Seguridad

**No exponer stack traces en producción**: En entorno de desarrollo se puede incluir información de depuración, pero en producción nunca se debe exponer stack traces, mensajes de error internos o información sensible.

```php
// Desarrollo - con información de depuración
if (app()->environment() === 'development') {
    return response()->json([
        'error' => $exception->getMessage(),
        'trace' => $exception->getTraceAsString()
    ], 500);
}

// Producción - sin información sensible
return response()->json([
    'error' => 'Error interno del servidor'
], 500);
```

---

### 10.2. Headers HTTP

#### Headers de Respuesta Estándar

| Header | Valor | Propósito |
|--------|-------|-----------|
| Content-Type | application/json | Indicar formato de respuesta JSON |
| Content-Type | text/html | Indicar formato de respuesta HTML |
| X-Content-Type-Options | nosniff | Prevenir MIME sniffing |
| X-Frame-Options | DENY | Prevenir clickjacking |

#### Headers de Seguridad

```php
// Establecer headers de seguridad en la respuesta
response()->headers([
    'X-Content-Type-Options' => 'nosniff',
    'X-Frame-Options' => 'DENY',
    'X-XSS-Protection' => '1; mode=block'
]);
```

#### Headers Personalizados (si son necesarios)

```php
// Headers personalizados para información de versión o rate limiting
response()->headers([
    'X-API-Version' => '1.0.0',
    'X-RateLimit-Limit' => '100',
    'X-RateLimit-Remaining' => '95'
]);
```

---

### 10.3. Configuración CORS (R7)

**Regla R7 — CORS y seguridad de orígenes**: Las aplicaciones que sirven a frontends deben respetar CORS.

#### Configuración de CORS para el Proyecto

**Orígenes permitidos**: Configurados vía variables de entorno
**Métodos permitidos**: GET, POST, PUT, PATCH, DELETE, OPTIONS
**Headers permitidos**: Content-Type, Authorization, X-Requested-With

#### Configuración Conceptual

```php
// En bootstrap o configuración de la aplicación
app()->cors([
    'origin' => env('CORS_ORIGIN', '*'),
    'methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
    'credentials' => true // Para permitir cookies/sesiones
]);
```

#### Configuración por Entorno

**Desarrollo**:
```php
// .env
CORS_ORIGIN=http://localhost:3000,http://localhost:5173
```

**Producción**:
```php
// .env.production
CORS_ORIGIN=https://miapp.com
```

#### Preflight Requests (OPTIONS)

Las preflight requests deben responderse correctamente. El módulo CORS de Leaf maneja esto automáticamente cuando se configura.

```php
// El módulo CORS responde automáticamente a OPTIONS
// No se requiere código adicional
```

#### Aplicación Global de Headers

Los headers CORS deben aplicarse globalmente a todas las respuestas de la aplicación, no solo a rutas específicas.

---

### 10.4. Patrones de Request Handling

#### Acceso a Parámetros de Ruta

```php
// En un controller
public function show($id)
{
    // $id viene de la ruta /projects/{id}
    $project = Project::find($id);
    
    return response()->json(['data' => $project]);
}
```

#### Acceso a Parámetros de Query

```php
// Obtener todos los parámetros de query
$params = request()->query();

// Obtener un parámetro específico
$page = request()->query('page', 1); // Default: 1
$perPage = request()->query('per_page', 10); // Default: 10
```

#### Acceso a Body (JSON)

```php
// Obtener todo el body JSON
$data = request()->json()->all();

// Obtener un campo específico del body
$name = request()->json()->get('name');
$email = request()->json()->get('email');

// Validar campos requeridos
if (!$data['name'] || !$data['email']) {
    return response()->json([
        'error' => 'Los campos name y email son requeridos'
    ], 400);
}
```

#### Acceso a Headers de Solicitud

```php
// Obtener todos los headers
$headers = request()->getHeaders();

// Verificar si existe un header específico
if ($headers->has('Authorization')) {
    $token = $headers->get('Authorization');
    $token = str_replace('Bearer ', '', $token);
}

// Obtener un header específico
$authorization = request()->getHeader('Authorization');
```

#### Acceso a Información de Solicitud

```php
// Obtener método HTTP
$method = request()->method(); // GET, POST, PUT, DELETE, etc.

// Obtener URI de la solicitud
$uri = request()->uri(); // Ej: /projects/123

// Obtener path de la solicitud
$path = request()->path(); // Ej: /projects/123

// Verificar si la solicitud espera JSON
if (request()->expectsJson()) {
    // Retornar respuesta JSON
    return response()->json(['data' => $data]);
} else {
    // Retornar vista
    return response()->view('projects.show', ['project' => $data]);
}
```

---

### 10.5. Manejo de Errores HTTP

#### Tabla de Errores HTTP Comunes

| Error | Status | Estructura | Cuándo usar |
|-------|--------|------------|-------------|
| Bad Request | 400 | `{ error: "..." }` | Datos inválidos o incompletos |
| Unauthorized | 401 | `{ error: "..." }` | No autenticado |
| Forbidden | 403 | `{ error: "..." }` | Autenticado pero sin permisos |
| Not Found | 404 | `{ error: "..." }` | Recurso no existe |
| Method Not Allowed | 405 | `{ error: "..." }` | Método HTTP no permitido |
| Unprocessable Entity | 422 | `{ error: "..." }` | Validación fallida |
| Internal Server Error | 500 | `{ error: "..." }` | Error interno |
| Service Unavailable | 503 | `{ error: "..." }` | Servicio no disponible |

#### Implementación en Controllers

```php
class ProjectController
{
    public function show($id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            return response()->json([
                'error' => 'Recurso no encontrado'
            ], 404);
        }
        
        return response()->json(['data' => $project]);
    }
    
    public function store()
    {
        $data = request()->json()->all();
        
        // Validar datos
        if (!$data['name'] || !$data['description']) {
            return response()->json([
                'error' => 'Los campos name y description son requeridos'
            ], 400);
        }
        
        try {
            $project = Project::create($data);
            return response()->json(['data' => $project], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el proyecto'
            ], 500);
        }
    }
}
```

#### Manejo de Errores en Middleware

```php
class AuthMiddleware
{
    public function call()
    {
        // Verificar si hay sesión activa
        if (!$this->hasSession()) {
            return response()->json([
                'error' => 'No autorizado. Debe iniciar sesión.'
            ], 401);
        }
        
        // Continuar con la solicitud
    }
}

class RoleAuthMiddleware
{
    protected $roles = [];
    
    public function __construct(array $roles = [])
    {
        $this->roles = $roles;
    }
    
    public function call()
    {
        // Verificar autenticación
        if (!$this->hasSession()) {
            return response()->json([
                'error' => 'No autorizado. Debe iniciar sesión.'
            ], 401);
        }
        
        // Verificar roles
        $user = $this->loadUserFromSession();
        $userRole = $user['role'] ?? 'guest';
        
        if (!in_array($userRole, $this->roles)) {
            return response()->json([
                'error' => 'No tiene permisos para acceder a este recurso'
            ], 403);
        }
    }
}
```

#### Manejo Global de Errores

```php
// En bootstrap o configuración de la aplicación
app()->setErrorHandler(function ($exception) {
    if (app()->environment() === 'development') {
        return response()->json([
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ], 500);
    }
    
    return response()->json([
        'error' => 'Error interno del servidor'
    ], 500);
});
```

#### Referencia

**Fuentes**:
- [`doc_consolidada/request-response-headers-cors-consolidada.md`](../doc_consolidada/request-response-headers-cors-consolidada.md)
- [`.governance/reglas_proyecto.md`](../.governance/reglas_proyecto.md) (R6, R7)
- [`doc:_respuestas-leaf-docs-researcher/Legado/request-response-headers-cors-guard-leaf.md`](doc:_respuestas-leaf-docs-researcher/Legado/request-response-headers-cors-guard-leaf.md)

---

## 7. Supuestos, Dudas y Decisiones Pendientes

### Vacíos de Información Reales

1. **Tiempo de ejecución del workflow**: No se conoce cuánto tiempo tarda el workflow de 9 pasos de OpenAI API. Esta información es crítica para decidir si se requiere un sistema de colas.

2. **Perfiles de usuario**: No se han definido roles, permisos o tipos de usuarios específicos más allá de la necesidad de autenticación.

3. **Estructura de datos**: No se ha definido la estructura específica de los proyectos (campos, tipos de datos, validaciones).

4. **Estrategia de almacenamiento de informes**: No se ha definido el formato exacto de los informes, su estructura, o cómo se organizan en el sistema de archivos.

5. **Contenedor de DI de Leaf**: No se ha confirmado si Leaf tiene contenedor de DI nativo.

6. **Framework de testing**: No se ha definido el framework de testing a utilizar (PHPUnit, Pest, Leaf Test).

7. **Herramientas de linting**: No se han definido las herramientas de linting a utilizar (PHP CS Fixer, PHPStan, Psalm).

### Preguntas Abiertas

1. ¿Cuánto tiempo tarda el workflow de 9 pasos de OpenAI API? (Si es > 30 segundos, se requerirán colas)

2. ¿Está el usuario dispuesto a implementar un sistema de colas propio si Leaf no tiene módulo nativo?

3. ¿Se prefiere simplicidad en MVP (ejecución síncrona) o mejor experiencia de usuario desde el inicio (ejecución asíncrona)?

4. ¿Qué información específica debe contener el JSON de un proyecto de inmueble?

5. ¿Qué estructura deben tener los informes generados por OpenAI API?

### Decisiones Pendientes

1. **Estrategia de workflows**: Decidir entre ejecución síncrona (más simple) o asíncrona con colas (mejor UX), dependiendo del tiempo de ejecución.

2. **Sistema de colas**: Si se requiere, decidir entre implementar sistema propio o usar librería externa.

3. **Inclusión de caché**: Decidir si se incluye caché desde el inicio o se pospone para fase posterior.

4. **Validación de módulos Leaf**: Verificar en documentación oficial online (https://leafphp.dev/modules/) si existen módulos nativos de workflows/colas.

5. **Contenedor de DI de Leaf**: Verificar en documentación oficial de Leaf si existe contenedor de DI nativo. Si no existe, decidir entre implementar contenedor propio o usar librería externa (PHP-DI).

6. **Framework de testing**: Definir framework de testing a utilizar (PHPUnit, Pest, Leaf Test) y configurar pipeline de CI para ejecutar tests.

7. **Herramientas de linting**: Definir herramientas de linting a utilizar (PHP CS Fixer + PHPStan), configurar reglas de estilo (PSR-12) y configurar pre-commit hooks.

8. **Método de despliegue (R8.b)**: Método de despliegue activo (Railway) documentado en `.governance/reglas_proyecto.md` y `.claude/agents/orquestador.md`. Responsable: `agt-core-config-deployment`.

---

## Historial de Cambios

| Fecha | Cambio |
|-------|--------|
| 2026-03-24 | Creación inicial del documento |
| 2026-03-24 | Confirmación de Gentelella como UI completa; adición de sección 1.1 con características, ventajas, adecuación y estrategia de futuro |
| 2026-03-24 | Confirmación de Sesiones + RBAC como sistema de autenticación para MVP; adición de sección 4.1 con características, ventajas, responsabilidades, flujo, RBAC, limitaciones y estrategia de futuro; actualización de sección 5 para incluir Sesiones y RBAC como módulos confirmados |
| 2026-03-24 | Adición de sección 5.1 - Sistema de Activación de Funcionalidades (Feature Toggle) con concepto general, diferenciación con módulos de Leaf, referencia a evaluación detallada |
| 2026-03-25 | Adición de índice de apartados al inicio del documento |
| 2026-03-25 | Adición de sección 6 - Sistema de Configuración con 6.1 Variables de Entorno, 6.2 Despliegue en Railway, 6.3 Manejo de Errores, 6.4 Inyección de Dependencias, 6.5 Docker, 6.6 Testing, 6.7 Linting, 6.8 Validaciones Realizadas; actualización de índice para incluir nueva sección |
| 2026-03-25 | Adición de sección 7 - Routing en Leaf PHP con 7.1 Route Groups y 7.2 Resource Routes |
| 2026-03-25 | Adición de sección 8 - Integración con Middleware de Autenticación con flujo completo, implementación conceptual y ejemplos de uso |
| 2026-03-25 | Adición de sección 9 - Integración con Feature Toggle System con rutas condicionales, middleware de verificación y combinación con RBAC |
| 2026-03-25 | Adición de sección 7.3 - Recursos Core Identificados con tabla de recursos, descripción detallada, grupos de rutas propuestos y recursos excluidos (Feature Toggle) |
| 2026-03-25 | Adición de sección 10 - Patrones de Request-Response HTTP con 10.1 Formatos de Respuesta (R6), 10.2 Headers HTTP, 10.3 Configuración CORS (R7), 10.4 Patrones de Request Handling, 10.5 Manejo de Errores HTTP; actualización de índice para incluir nueva sección |
| 2026-03-25 | Actualización de sección 7 (Supuestos, Dudas y Decisiones Pendientes) con nuevos vacíos de información y decisiones pendientes relacionadas con configuración y despliegue |
| 2026-03-25 | Ampliación de sección 5.1 - Sistema de Activación de Funcionalidades (Feature Toggle) con compatibilidad con filosofía de Leaf, mecanismos de Leaf que ayudan a implementar el control, separación de responsabilidades, relación con autenticación, consideraciones conceptuales, arquitectura recomendada y recomendaciones específicas para MVP |
