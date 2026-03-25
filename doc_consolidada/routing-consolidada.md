# Routing - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**:
- doc_respuestas-leaf-docs-researcher/Legado/routing-leaf.md
- doc_proyecto/concepto-de-proyecto.md
- SETUP_COMPLETE.md
- PROJECT_README.md

---

## Resumen Ejecutivo

El sistema de routing en Leaf conecta las URLs (solicitudes HTTP) con los controladores que manejan esas solicitudes. Leaf proporciona un sistema flexible que soporta rutas básicas, grupos de rutas, routing dinámico y middleware.

**Decisión Confirmada**: Uso del sistema de routing de Leaf para el proyecto.

---

## ¿Qué es Routing en Leaf?

### Concepto General

El routing en Leaf es el sistema que conecta las URLs (solicitudes HTTP) con los controladores que manejan esas solicitudes. Es el componente que determina qué código se ejecuta cuando un usuario visita una URL específica.

### Función Principal del Router

- Recibir la solicitud HTTP (URL, método, headers, etc.)
- Encontrar la ruta que coincide con la solicitud
- Ejecutar el controlador asociado a esa ruta
- Devolver la respuesta del controlador al usuario

---

## Basic Routing

### Definición de Rutas Básicas

**Sintaxis confirmada desde el código**:

```php
// GET route
app()->get('/', function () {
    return 'Hello World';
});

// POST route
app()->post('/submit', function () {
    return 'Form submitted';
});

// Route con parámetros
app()->get('/users/{id}', function ($id) {
    return "User $id";
});
```

### Características

- **Simplicidad**: Sintaxis clara y fácil de entender
- **Flexibilidad**: Soporta múltiples métodos HTTP (GET, POST, PUT, DELETE, etc.)
- **Parámetros**: Permite definir parámetros en las rutas (ej: `/users/{id}`)
- **Callbacks**: Permite definir funciones anónimas como controladores

### Uso en el Proyecto

**Confirmado desde [`routes/api.php`](../routes/api.php) y SETUP_COMPLETE.md**:

**Rutas API confirmadas**:

```php
// Grupo /api/v1
app()->group('/api/v1', function () {
    // Endpoints públicos
    app()->get('/health', function () {
        return response()->json(['status' => 'ok']);
    });

    app()->get('/info', function () {
        return response()->json([
            'version' => '1.0.0',
            'framework' => 'Leaf PHP'
        ]);
    });

    app()->post('/echo', function () {
        return response()->json(request()->body());
    });

    // CRUD de usuarios
    app()->get('/users', [UserController::class, 'index']);
    app()->get('/users/{id}', [UserController::class, 'show']);
    app()->post('/users', [UserController::class, 'store']);
    app()->put('/users/{id}', [UserController::class, 'update']);
    app()->delete('/users/{id}', [UserController::class, 'destroy']);
});
```

**Rutas Web confirmadas** (fuente: SETUP_COMPLETE.md):
- `/` - Welcome
- `/health` - Health check

---

## Route Groups

### Definición de Grupos de Rutas

Los grupos de rutas permiten agrupar múltiples rutas bajo un prefijo común y aplicar middleware a todo el grupo.

### Sintaxis

```php
// Grupo con prefijo y middleware
app()->group('/api/v1', ['middleware' => 'auth'], function () {
    app()->get('/users', function () {
        return response()->json(User::all());
    });

    app()->post('/users', function () {
        return response()->json(User::create(request()->body()));
    });

    app()->get('/users/{id}', function ($id) {
        return response()->json(User::find($id));
    });
});
```

### Características

- **Prefijo común**: Todas las rutas del grupo comparten el prefijo `/api/v1`
- **Middleware compartido**: El middleware `auth` se aplica a todas las rutas del grupo
- **Organización**: Mejor organización de rutas relacionadas
- **Eficiencia**: Menos código duplicado (no repetir prefijo en cada ruta)

### Uso Recomendado

- **Para autenticación**: Agrupar rutas protegidas bajo un grupo con middleware de autenticación
- **Para versiones de API**: Agrupar rutas de cada versión bajo su propio prefijo (`/api/v1`, `/api/v2`, etc.)
- **Para recursos**: Agrupar rutas de un recurso bajo un prefijo (`/users`, `/projects`, etc.)

---

## Dynamic Routing

### Definición de Routing Dinámico

El routing dinámico permite definir rutas con patrones que coinciden con múltiples URLs.

### Sintaxis

```php
// Patrón con parámetro dinámico
app()->get('/users/{id}', function ($id) {
    return "User $id";
});

// Patrón con múltiples parámetros
app()->get('/posts/{category}/{id}', function ($category, $id) {
    return "Post $category, ID $id";
});
```

### Características

- **Flexibilidad**: Una sola ruta maneja múltiples URLs
- **Parámetros**: Captura variables de la URL para usar en el controlador
- **Patrones**: Soporta expresiones regulares para patrones complejos

### Uso en el Proyecto

**Confirmado desde [`src/Router.php`](../src/Router.php)**:
El Router de Leaf soporta:
- Coincidencia exacta de rutas
- Parámetros en rutas (ej: `{id}`, `{category}/{id}`)
- Reemplazo de parámetros en patrones
- Grupos de rutas con middleware compartido

---

## Middleware

### Definición de Middleware en Leaf

El middleware es código que se ejecuta antes o después de que el router encuentre una ruta. Se usa para:
- Verificar autenticación
- Validar permisos
- Modificar la solicitud o respuesta
- Logging
- CSRF protection
- Rate limiting

### Tipos de Middleware

- **Middleware de ruta**: Se ejecuta para una ruta específica
- **Middleware global**: Se ejecuta para todas las rutas
- **Middleware de grupo**: Se ejecuta para todas las rutas de un grupo
- **Middleware de método**: Se ejecuta solo para ciertos métodos HTTP

### Uso en el Proyecto

**Confirmado desde [`app/Middleware/AuthMiddleware.php`](../app/Middleware/AuthMiddleware.php)**:
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

        // Continuar con la solicitud
    }
}
```

**Aplicación a rutas**:
```php
// Aplicar middleware a una ruta
app()->get('/protected', [AuthController::class, 'index'])->middleware('auth');

// Aplicar middleware a un grupo
app()->group('/api/v1', ['middleware' => 'auth'], function () {
    app()->get('/users', [UserController::class, 'index']);
});
```

---

## Route Groups

### Route Groups en Leaf

Los grupos de rutas permiten agrupar múltiples rutas bajo un prefijo común y aplicar middleware a todo el grupo.

### Sintaxis

```php
// Grupo con prefijo y middleware
app()->group('/api/v1', ['middleware' => 'auth'], function () {
    app()->get('/users', function () {
        return response()->json(User::all());
    });

    app()->post('/users', function () {
        return response()->json(User::create(request()->body()));
    });

    app()->get('/users/{id}', function ($id) {
        return response()->json(User::find($id));
    });
});
```

### Características

- **Prefijo común**: Todas las rutas del grupo comparten el prefijo `/api/v1`
- **Middleware compartido**: El middleware `auth` se aplica a todas las rutas del grupo
- **Organización**: Mejor organización de rutas relacionadas
- **Eficiencia**: Menos código duplicado (no repetir prefijo en cada ruta)

### Uso Recomendado

- **Para autenticación**: Agrupar rutas protegidas bajo un grupo con middleware de autenticación
- **Para versiones de API**: Agrupar rutas de cada versión bajo su propio prefijo (`/api/v1`, `/api/v2`, etc.)
- **Para recursos**: Agrupar rutas de un recurso bajo un prefijo (`/users`, `/projects`, etc.)

---

## Resource Routes

### Resource Routes en Leaf

Las rutas de recursos permiten definir automáticamente las rutas CRUD para un recurso.

### Sintaxis

```php
// Definir rutas CRUD para un recurso
app()->resource('/users', UserController::class);
```

Esto crea automáticamente las siguientes rutas:
- `GET /users` - index
- `GET /users/create` - create
- `POST /users` - store
- `GET /users/{id}` - show
- `GET /users/{id}/edit` - edit
- `PUT/PATCH /users/{id}` - update
- `DELETE /users/{id}` - destroy

### Uso en el Proyecto

**Confirmado desde [`concepto-de-proyecto.md`](../doc_proyecto/concepto-de-proyecto.md)**:
El proyecto usa rutas de recursos para gestión de proyectos, usuarios y otros recursos.

---

## Rutas Confirmadas

**Confirmado desde SETUP_COMPLETE.md y PROJECT_README.md**:

### Endpoints Públicos

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

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Sistema de routing flexible | routing-leaf.md | ✅ Confirmado |
| Rutas básicas con parámetros | routing-leaf.md | ✅ Confirmado |
| Grupos de rutas con middleware | routing-leaf.md | ✅ Confirmado |
| Routing dinámico con patrones | routing-leaf.md | ✅ Confirmado |
| AuthMiddleware para protección | routing-leaf.md | ✅ Confirmado |
| Resource routes para CRUD | concepto-de-proyecto.md | ✅ Confirmado |
| Rutas separadas web.php y api.php | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |
| CRUD de usuarios implementado | SETUP_COMPLETE.md | ✅ Confirmado |
| End públicos confirmados | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |

---

## Recomendaciones

### Para MVP

✅ **Usar grupos de rutas**
- Agrupar rutas por recurso (`/projects`, `/users`)
- Agrupar rutas protegidas con middleware de autenticación
- Agrupar rutas de API bajo prefijos de versión

### Para Organización

✅ **Separar rutas por tipo**
- `routes/web.php` - Rutas para vistas HTML
- `routes/api.php` - Rutas para API JSON

### Para Seguridad

✅ **Aplicar middleware apropiado**
- Middleware de autenticación para rutas protegidas
- Middleware de CORS para rutas de API
- Middleware de validación para rutas que aceptan datos

---

## Referencias a Documentos Fuente

1. **routing-leaf.md** - Routing en Leaf PHP
2. **concepto-de-proyecto.md** - Concepto general del proyecto
3. **SETUP_COMPLETE.md** - Documentación del setup completado con rutas implementadas
4. **PROJECT_README.md** - README principal del proyecto con información de routing
5. **routes/web.php** - Archivo de rutas web
6. **routes/api.php** - Archivo de rutas API
