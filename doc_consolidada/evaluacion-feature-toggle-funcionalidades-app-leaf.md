# Evaluación: Feature Toggle System (Funcionalidades de Aplicación) en Leaf

**Agente**: leaf-docs-researcher
**Fecha**: 2026-03-24
**Fuente**: Documentación oficial de Leaf PHP + análisis del proyecto actual
**Objetivo Principal**: Evaluar cómo encaja un sistema de feature toggle de funcionalidades de aplicación con Leaf sin romper su filosofía

---

## 1. Distinción Importante: Funcionalidades de Aplicación vs Módulos de Leaf

### Clarificación Conceptual

**Funcionalidades de la aplicación**: Lógica de negocio específica de tu proyecto (ej: gestión de proyectos, análisis con OpenAI API, informes, CRM, etc.)

**Módulos de Leaf**: Componentes del framework instalados vía Composer (leafs/leaf, leafs/http, leafs/db, etc.) que proporcionan capacidades genéricas

### Diferencia Clave

| Aspecto | Funcionalidades de Aplicación | Módulos de Leaf |
|----------|-------------------------------|-----------------|
| **Origen** | Desarrolladas por ti | Instaladas desde Composer |
| **Propósito** | Lógica de negocio específica | Capacidades genéricas del framework |
| **Activación** | Controlada por feature toggles | Siempre disponibles |
| **Dependencia** | Pueden depender de módulos de Leaf | Independientes de tu lógica |
| **Ejemplo** | "Gestión de proyectos" | "leafs/db", "leafs/http" |

### Implicación para el Sistema de Feature Toggle

**El sistema de feature toggle controla EXCLUSIVAMENTE las funcionalidades de tu aplicación**, no los módulos de Leaf.

**Ejemplo conceptual**:
- Si desactivas el módulo de "gestión de proyectos" de tu aplicación:
  - Las rutas de proyectos no están disponibles
  - Los elementos de menú de proyectos no se muestran
  - Los endpoints de proyectos retornan 404
  - Pero `leafs/db` sigue funcionando normalmente
  - Pero tu aplicación no lo usa para proyectos

---

## 2. ¿Encaja el Sistema de Feature Toggle con la Filosofía de Leaf?

### Análisis de la Filosofía de Leaf

**Características de Leaf**:
- **Minimalista**: Prioriza simplicidad, solo incluye lo esencial
- **No opinativo**: No impone arquitecturas específicas
- **Flexible**: Permite diferentes enfoques de organización
- **Convenciones**: Sigue convenciones de PHP (PSR-4, PSR-12, etc.)

### Evaluación de Compatibilidad

| Aspecto | Filosofía de Leaf | Sistema de Feature Toggle | Compatibilidad |
|----------|---------------------|------------------------|---------------|
| **Minimalismo** | Prioriza simplicidad | Añade una capa de configuración | ⚠️ Requiere diseño cuidadoso |
| **No opinativo** | No impone arquitectura | Define una arquitectura de configuración | ⚠️ Puede parecer impuesto |
| **Flexibilidad** | Flexible, permite diferentes enfoques | Flexible, configurable | ✅ Compatible |
| **Convenciones** | Sigue convenciones PHP | Puede seguir convenciones PHP | ✅ Compatible |
| **Separación de responsabilidades** | Lógica de negocio separada del framework | Lógica de activación separada de la lógica de negocio | ✅ Compatible |

### Conclusión sobre Compatibilidad

**El sistema de feature toggle ENCAJA con la filosofía de Leaf**, pero con consideraciones importantes:

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

---

## 3. Mecanismos de Leaf que Ayudan a Implementar este Control

### 3.1. Middleware

**Qué ofrece Leaf**:
- Estructura de middleware ([`AuthMiddleware.php`](../app/Middleware/AuthMiddleware.php))
- Capacidad de aplicar middleware a rutas específicas
- Capacidad de encadenar múltiples middleware

**Cómo ayuda al sistema de feature toggle**:

**Middleware para verificar si un módulo está activo**:
```php
// Concepto, no implementación
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

**Middleware para verificar si un usuario tiene acceso a un módulo**:
```php
// Concepto, no implementación
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

**Aplicación a rutas**:
```php
// Concepto, no implementación
// Rutas solo disponibles si el módulo está activo
app()->group(['middleware' => 'module_active:projects'], function () {
    app()->get('/projects', [ProjectController::class, 'index']);
    app()->get('/projects/create', [ProjectController::class, 'create']);
});

// Rutas solo disponibles si el módulo está activo Y el usuario tiene acceso
app()->group(['middleware' => ['module_active:projects', 'module_access:projects'], function () {
    app()->get('/projects/{id}', [ProjectController::class, 'show']);
    app()->post('/projects/{id}', [ProjectController::class, 'update']);
});
```

### 3.2. Routing

**Qué ofrece Leaf**:
- Sistema de routing flexible
- Capacidad de definir rutas con condiciones
- Capacidad de agrupar rutas

**Cómo ayuda al sistema de feature toggle**:

**Rutas condicionales por módulo activo**:
```php
// Concepto, no implementación
// Solo definir rutas si el módulo está activo
if ($this->isModuleActive('projects')) {
    app()->get('/projects', [ProjectController::class, 'index']);
    app()->get('/projects/create', [ProjectController::class, 'create']);
}
```

**Rutas dinámicas basadas en configuración**:
```php
// Concepto, no implementación
// Cargar rutas disponibles desde configuración
$activeModules = $this->getActiveModules();

foreach ($activeModules as $module) {
    $this->registerModuleRoutes($module);
}
```

### 3.3. Configuración

**Qué ofrece Leaf**:
- Estructura de configuración ([`config/app.php`](../config/app.php))
- Capacidad de cargar configuración desde múltiples fuentes
- Capacidad de acceder a configuración desde cualquier parte de la aplicación

**Cómo ayuda al sistema de feature toggle**:

**Cargar configuración de módulos activos**:
```php
// Concepto, no implementación
// En config/app.php o bootstrap
$activeModules = $this->loadFeatureToggles();
app()->set('active_modules', $activeModules);
```

**Acceso a configuración desde controllers y middleware**:
```php
// Concepto, no implementación
// En cualquier parte de la aplicación
$activeModules = app()->get('active_modules');
$moduleConfig = app()->get('module_config');
```

### 3.4. Vistas (Blade)

**Qué ofrece Leaf**:
- Blade como motor de vistas recomendado
- Capacidad de pasar datos a vistas
- Capacidad de incluir vistas parciales

**Cómo ayuda al sistema de feature toggle**:

**Elementos de menú condicionales**:
```php
// Concepto, no implementación
// En views/layouts/app.blade.php
@if (in_array('projects', $activeModules))
    <li><a href="/projects">Proyectos</a></li>
@endif

@if (in_array('reports', $activeModules))
    <li><a href="/reports">Informes</a></li>
@endif
```

**Submódulos condicionales**:
```php
// Concepto, no implementación
// En views/auth/login.blade.php
@if ($authConfig['submodules']['register']['active'])
    <a href="/auth/register">Registrarse</a>
@endif
```

---

## 4. Separación de Responsabilidades

### 4.1. Responsabilidades que Apoya Leaf Directamente

**Confirmado desde el proyecto**:
- **Middleware**: Estructura de middleware ([`AuthMiddleware.php`](../app/Middleware/AuthMiddleware.php))
- **Routing**: Sistema de routing flexible
- **Configuración**: Estructura de configuración ([`config/app.php`](../config/app.php))
- **Base de datos**: Capa de base de datos ([`leafs/db`](../SETUP_COMPLETE.md:31))
- **Vistas**: Blade como motor de vistas

**Qué apoya directamente**:
- Estructura para crear middleware personalizados
- Sistema flexible para definir rutas
- Estructura para cargar y acceder configuración
- Capa para acceder a base de datos
- Motor de vistas para elementos condicionales

### 4.2. Responsabilidades que Recaen Completamente en el Desarrollador

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

**Lógica de negocio**:
- Implementar lógica para verificar si un módulo está activo
- Implementar lógica para verificar si un usuario tiene acceso
- Integrar esta lógica con el sistema de autenticación
- Implementar lógica para filtrar elementos de menú según módulos activos

**Vistas**:
- Implementar elementos de menú condicionales
- Implementar submódulos condicionales
- Integrar con la configuración de módulos activos

**CRUD de gestión**:
- Aunque no forma parte del MVP, diseñar estructura para el CRUD de gestión
- Diseñar la estructura para el CRUD de permisos de roles

### 4.3. Resumen de Responsabilidades

| Responsabilidad | Leaf | Desarrollador |
|-----------------|--------|---------------|
| Estructura de middleware | ✅ Sí | ❌ No |
| Sistema de routing | ✅ Sí | ❌ No |
| Estructura de configuración | ✅ Sí | ❌ No |
| Capa de base de datos | ✅ Sí | ❌ No |
| Motor de vistas (Blade) | ✅ Sí | ❌ No |
| Tablas de feature_toggles | ❌ No | ✅ Sí |
| Tablas de role_module_permissions | ❌ No | ✅ Sí |
| Modelo FeatureToggle | ❌ No | ✅ Sí |
| Modelo RoleModulePermission | ❌ No | ✅ Sí |
| ModuleActiveMiddleware | ❌ No | ✅ Sí |
| ModuleAccessMiddleware | ❌ No | ✅ Sí |
| Lógica de verificación de módulos activos | ❌ No | ✅ Sí |
| Lógica de verificación de acceso a módulos | ❌ No | ✅ Sí |
| Integración con autenticación | ⚠️ Parcial | ⚠️ Parcial |
| Elementos de menú condicionales | ❌ No | ✅ Sí |
| CRUD de gestión de feature toggles | ❌ No | ✅ Sí (no en MVP) |

---

## 5. Relación con el Manejo de Autenticación (Sesiones) en Leaf

### 5.1. Separación Clara de Responsabilidades

**Sistema de autenticación de Leaf**:
- Manejo de sesiones (crear, destruir, leer)
- Verificación de si hay sesión activa
- Obtención del usuario autenticado

**Sistema de feature toggle de la aplicación**:
- Verificación de si un módulo está activo
- Verificación de si un usuario tiene acceso a un módulo
- Control de qué elementos de UI se muestran
- Control de qué rutas están disponibles

**Punto clave**: Estos dos sistemas deben trabajar juntos, pero mantener responsabilidades separadas.

### 5.2. Flujo Conceptual Integrado

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

### 5.3. Implementación Conceptual

**Al hacer login**:
```php
// Concepto, no implementación
// Después de autenticar al usuario
$user = $auth->user();
$accessibleModules = $roleModulePermission->getAccessibleModules($user['role_id']);

// Guardar módulos accesibles en la sesión
session()->set('accessible_modules', $accessibleModules);
```

**Middleware integrado**:
```php
// Concepto, no implementación
// AuthMiddleware verifica sesión
class AuthMiddleware
{
    public function call()
    {
        // Verificar si hay sesión
        if (!$this->hasSession()) {
            return redirect('/auth/login');
        }
        
        // Cargar usuario en contexto
        app()->set('auth.user', $this->user());
    }
}

// ModuleActiveMiddleware verifica módulo activo
class ModuleActiveMiddleware
{
    public function call()
    {
        $moduleName = $this->getModuleName();
        
        // Verificar si el módulo está activo
        if (!$this->featureToggle->isModuleActive($moduleName)) {
            response()->json(['error' => 'Module not active'], 404);
            exit;
        }
    }
}

// ModuleAccessMiddleware verifica acceso
class ModuleAccessMiddleware
{
    public function call()
    {
        $moduleName = $this->getModuleName();
        $accessibleModules = session()->get('accessible_modules', []);
        
        // Verificar si el usuario tiene acceso
        if (!in_array($moduleName, $accessibleModules)) {
            response()->json(['error' => 'Forbidden'], 403);
            exit;
        }
    }
}
```

**Aplicación a rutas**:
```php
// Concepto, no implementación
// Aplicar middleware en orden correcto
app()->group(['middleware' => ['auth', 'module_active:projects', 'module_access:projects'], function () {
    app()->get('/projects', [ProjectController::class, 'index']);
    app()->get('/projects/create', [ProjectController::class, 'create']);
});
```

### 5.4. Consideraciones Importantes

**1. Orden de verificación**:
- Primero verificar si el usuario está autenticado (AuthMiddleware)
- Luego verificar si el módulo está activo (ModuleActiveMiddleware)
- Finalmente verificar si el usuario tiene acceso (ModuleAccessMiddleware)

**2. Caché de configuración**:
- Cachear la configuración de feature toggles para evitar consultas repetidas
- Invalidar caché cuando se actualiza la configuración

**3. Invalidación de sesiones**:
- Cuando se cambian los permisos de un rol, invalidar las sesiones de los usuarios con ese rol
- Esto asegura que los usuarios obtengan los nuevos permisos al volver a iniciar sesión

**4. Separación clara**:
- El sistema de autenticación de Leaf maneja sesiones
- El sistema de feature toggle maneja activación de módulos
- Ambos sistemas se integran pero mantienen responsabilidades separadas

---

## 6. Consideraciones Conceptuales para un Diseño Limpio y Coherente

### 6.1. Minimalismo

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

### 6.2. Separación de Responsabilidades

**Filosofía de Leaf**: Lógica de negocio separada del framework.

**Cómo aplicar al sistema de feature toggle**:
- Mantener el sistema de feature toggle separado de la lógica de negocio principal
- No mezclar la lógica de activación con la lógica de negocio de los módulos
- Usar middleware para separar la lógica de activación de la lógica de negocio

**Ejemplo**:
- La lógica de gestión de proyectos no debe saber si el módulo está activo
- El middleware verifica si el módulo está activo antes de llegar al controller
- El controller solo se preocupa por la lógica de negocio de proyectos

### 6.3. Convenciones

**Filosofía de Leaf**: Sigue convenciones de PHP (PSR-4, PSR-12, etc.)

**Cómo aplicar al sistema de feature toggle**:
- Seguir convenciones de nomenclatura para tablas y modelos
- Seguir convenciones de PHP para namespaces y clases
- Usar la estructura de configuración de Leaf

**Ejemplo**:
- Nombres de tablas: snake_case (feature_toggles, role_module_permissions)
- Nombres de clases: PascalCase (FeatureToggle, RoleModulePermission)
- Namespaces: App\Models, App\Middleware

### 6.4. No Opinatividad

**Filosofía de Leaf**: No impone arquitecturas específicas.

**Cómo aplicar al sistema de feature toggle**:
- No imponer una forma específica de usar el sistema
- Permitir diferentes estrategias de configuración
- No imponer una estructura específica de módulos
- Permitir que el desarrollador decida la mejor organización

**Ejemplo**:
- No imponer si los módulos deben estar en una sola tabla o múltiples tablas
- No imponer si los submódulos deben ser parte de la misma tabla o tablas separadas
- Permitir que el desarrollador decida la mejor estructura para su caso

### 6.5. Flexibilidad

**Filosofía de Leaf**: Flexible, permite diferentes enfoques.

**Cómo aplicar al sistema de feature toggle**:
- Permitir diferentes estrategias de activación/desactivación
- Permitir diferentes estrategias de control de acceso
- Permitir diferentes estrategias de organización de módulos
- Permitir que el sistema evolucione con el tiempo

**Ejemplo**:
- Permitir activar/desactivar módulos completos o solo submódulos
- Permitir diferentes estrategias de control de acceso (por rol, por usuario, por permiso)
- Permitir diferentes estrategias de organización (por dominio, por funcionalidad)

---

## 7. Arquitectura Recomendada para el Sistema de Feature Toggle

### 7.1. Estructura de Datos

**Tablas principales**:

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

-- Datos iniciales para roles
INSERT INTO roles (name, description) VALUES
('usuario', 'Rol de usuario con acceso básico'),
('administrador', 'Rol de administrador con acceso completo');

-- Datos iniciales para feature toggles (ejemplo para módulo de proyectos)
INSERT INTO feature_toggles (module, submodule, is_active, description) VALUES
('projects', NULL, true, 'Módulo de gestión de proyectos'),
('projects', 'create', true, 'Submódulo de creación de proyectos'),
('projects', 'edit', true, 'Submódulo de edición de proyectos'),
('projects', 'delete', true, 'Submódulo de eliminación de proyectos'),
('projects', 'view', true, 'Submódulo de visualización de proyectos');

-- Datos iniciales para permisos de roles (ejemplo)
-- Usuario tiene acceso a visualización de proyectos
INSERT INTO role_module_permissions (role_id, module, can_access) VALUES
(1, 'projects', true);

-- Administrador tiene acceso completo a proyectos
INSERT INTO role_module_permissions (role_id, module, can_access) VALUES
(2, 'projects', true);
```

### 7.2. Estructura de Código

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

### 7.3. Flujo de Trabajo

**1. Inicio de la aplicación**:
- Cargar configuración de feature toggles
- Cachear configuración para mejorar rendimiento

**2. Login del usuario**:
- Autenticar usuario
- Cargar módulos accesibles por el rol del usuario
- Guardar módulos accesibles en la sesión

**3. Solicitud del usuario**:
- Verificar si el usuario está autenticado (AuthMiddleware)
- Verificar si el módulo está activo (ModuleActiveMiddleware)
- Verificar si el usuario tiene acceso (ModuleAccessMiddleware)
- Procesar solicitud o retornar error

**4. Renderizado de vista**:
- Leer módulos activos desde configuración
- Leer módulos accesibles desde sesión
- Renderizar elementos condicionales (menús, submódulos)

**5. Gestión de configuración** (no en MVP):
- CRUD para activar/desactivar módulos
- CRUD para asignar permisos a roles
- Invalidar caché cuando se actualiza la configuración

---

## 8. Recomendaciones Específicas

### Para MVP

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

### Para Fase Posterior

**Recomendación**: Evaluar mejoras cuando sea necesario.

**Mejoras posibles**:
- CRUD de gestión de feature toggles
- CRUD de gestión de permisos de roles
- Interfaz de administración para gestionar el sistema
- Caché más sofisticado para la configuración
- Auditoría de cambios en la configuración
- Historial de cambios de módulos

---

## Clasificación de la Información

| Tipo | Contenido |
|------|-----------|
| **Hecho documentado** | AuthMiddleware.php existe; config/app.php existe; leafs/db existe; Blade es motor de vistas recomendado |
| **Observación del proyecto** | El scaffold tiene estructura de middleware, configuración y base de datos |
| **Información no verificada** | Funcionalidades específicas de Leaf para configuración dinámica |
| **Conocimiento general** | Diseño de sistemas de feature toggle; integración con sesiones; consideraciones arquitectónicas |

---

## Supuestos, Dudas y Decisiones Pendientes

### Preguntas Abiertas

1. **Funcionalidades específicas de Leaf**: ¿Tiene Leaf funciones nativas para cargar configuración desde base de datos?

2. **Estrategia de caché**: ¿Debería cachearse la configuración de feature toggles? Si es así, ¿cómo invalidar el caché?

3. **Nivel de granularidad**: ¿Debería permitirse activar/desactivar solo módulos completos o también submódulos?

4. **Interfaz de administración**: ¿Debería incluirse interfaz de administración para gestionar el sistema en MVP o en fase posterior?

### Decisiones Pendientes

1. **Estrategia de implementación**: Decidir si implementar el sistema completo desde el inicio o progresivamente

2. **Estructura de configuración**: Decidir si usar una sola tabla para módulos y submódulos o tablas separadas

3. **Invalidación de sesiones**: Decidir cuándo invalidar las sesiones cuando se actualizan los permisos de roles

4. **CRUD de gestión**: Decidir si incluir CRUD de gestión en MVP o en fase posterior

---

## Conclusión

**El sistema de feature toggle de funcionalidades de aplicación ENCAJA con la filosofía de Leaf**, pero requiere implementación cuidadosa para no añadir fricción innecesaria.

**Consideraciones clave**:
- Mantener separación clara entre lógica de Leaf (autenticación) y lógica de aplicación (feature toggles)
- Usar las capacidades nativas de Leaf (middleware, routing, configuración, vistas)
- Seguir las convenciones de PHP y Leaf
- Implementar solo lo esencial para el MVP
- Mantener el sistema simple y mantenible

**Recomendación**: Implementar el sistema de forma progresiva, empezando con la estructura base y middleware, y agregando el CRUD de gestión en fase posterior.
