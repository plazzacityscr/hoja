# Feature Toggle - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**: 
- doc_proyecto/evaluacion-feature-toggle-funcionalidades-app-leaf.md
- doc_proyecto/concepto-de-proyecto.md
- doc_respuestas-leaf-docs-researcher/rc_analisis-estrategico-agentes-multiagente.md

---

## Resumen Ejecutivo

El proyecto implementará un **Sistema de Feature Toggle** para activar/desactivar funcionalidades de la aplicación. Este sistema controla EXCLUSIVAMENTE las funcionalidades de la aplicación (lógica de negocio específica), no los módulos de Leaf.

**Decisión Confirmada**: Sistema de Feature Toggle para funcionalidades de aplicación.

---

## Distinción Importante: Funcionalidades de Aplicación vs Módulos de Leaf

### Clarificación Conceptual

| Aspecto | Funcionalidades de la Aplicación | Módulos de Leaf |
|----------|-----------------------------------|------------------|
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

## Compatibilidad con la Filosofía de Leaf

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

## Mecanismos de Leaf que Ayudan a Implementar este Control

### 1. Middleware

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

### 2. Routing

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

### 3. Configuración

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

---

## Decisiones Pendientes

| Decisión | Estado | Impacto |
|----------|--------|---------|
| Estrategia de caché para feature toggles | ⏳ Pendiente | Rendimiento |
| Nivel de granularidad de feature toggles (módulos vs submódulos) | ⏳ Pendiente | Flexibilidad |
| Cuándo incluir CRUD de gestión de feature toggles | ⏳ Pendiente | Administración |

---

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Sistema de Feature Toggle para funcionalidades | concepto-de-proyecto.md, rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |
| Distinción: funcionalidades vs módulos Leaf | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Compatibilidad con filosofía de Leaf | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Middleware para verificar módulo activo | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Rutas condicionales por módulo | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |
| Configuración de módulos activos | evaluacion-feature-toggle-funcionalidades-app-leaf.md | ✅ Confirmado |

---

## Recomendaciones

### Para MVP

✅ **Implementar sistema simple de feature toggles**
- Usar configuración en archivos o base de datos
- Middleware para verificar módulos activos
- Rutas condicionales por módulo activo

### Para Implementación

✅ **Seguir convenciones de Leaf**
- Usar middleware de Leaf
- Usar sistema de routing de Leaf
- Usar sistema de configuración de Leaf

### Para Futuro

⏳ **Evaluar mejoras**
- CRUD de gestión de feature toggles
- Caché de configuración de módulos
- Granularidad más fina (submódulos)

---

## Referencias a Documentos Fuente

1. **evaluacion-feature-toggle-funcionalidades-app-leaf.md** - Evaluación del sistema de feature toggle en Leaf
2. **concepto-de-proyecto.md** - Concepto general del proyecto con feature toggle
3. **rc_analisis-estrategico-agentes-multiagente.md** - Análisis estratégico del sistema multi-agente
