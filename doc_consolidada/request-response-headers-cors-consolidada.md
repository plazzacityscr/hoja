# Request, Response, Headers, CORS y Guard - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**:
- doc_respuestas-leaf-docs-researcher/request-response-headers-cors-guard-leaf.md
- doc_proyecto/concepto-de-proyecto.md
- SETUP_COMPLETE.md
- PROJECT_README.md

---

## Resumen Ejecutivo

Leaf proporciona objetos para manejar solicitudes HTTP (Request), respuestas HTTP (Response), headers HTTP y CORS. Estos componentes son fundamentales para el manejo de HTTP en la aplicación.

**Decisión Confirmada**: Uso de los componentes Request, Response, Headers y CORS de Leaf.

---

## Request

### Qué es Request en Leaf

El objeto Request en Leaf representa la solicitud HTTP entrante y proporciona métodos para acceder a la información de la solicitud.

### Características Principales

**Confirmado desde el código y SETUP_COMPLETE.md**:
- **Acceso a parámetros**: `request()->get()`, `request()->post()`, `request()->json()->all()`
- **Acceso a headers**: `request()->getHeader()`, `request()->getHeaders()`
- **Acceso a información de la solicitud**: `request()->method()`, `request()->uri()`, `request()->path()`
- **Acceso a cuerpo**: `request()->body()`, `request()->input()`

### Métodos Disponibles

```php
// Obtener método HTTP
$method = request()->method(); // GET, POST, PUT, DELETE, etc.

// Obtener URI de la solicitud
$uri = request()->uri(); // Ej: /users/123

// Obtener parámetros de la solicitud
$input = request()->input(); // Array de todos los parámetros

// Obtener un parámetro específico
$id = request()->get('id'); // Parámetro de ruta

// Obtener el cuerpo de la solicitud
$body = request()->body(); // Para solicitudes POST/PUT
```

### Uso en el Proyecto

**Confirmado desde [`app/Controllers/UserController.php`](../app/Controllers/UserController.php)**:
```php
public function store()
{
    $data = request()->json()->all();

    if (!$data['name'] || !$data['email'] || !$data['password']) {
        return response()->json([
            'success' => false,
            'message' => 'Name, email and password are required'
        ], 400);
    }

    // Crear usuario
    $user = (new User())->create($data);

    return response()->json([
        'success' => true,
        'data' => $user
    ], 201);
}
```

---

## Response

### Qué es Response en Leaf

El objeto Response en Leaf representa la respuesta HTTP que se envía al cliente. Proporciona métodos para configurar la respuesta, enviar datos, y manejar diferentes tipos de respuestas.

### Características Principales

**Confirmado desde el código y SETUP_COMPLETE.md**:
- **Métodos de respuesta**: `json()`, `view()`, `download()`, `redirect()`
- **Códigos de estado HTTP**: Métodos para establecer códigos de estado (200, 404, 500, etc.)
- **Headers**: Métodos para agregar headers a la respuesta
- **Cookies**: Métodos para manejar cookies
- **Detención inmediata**: Método para enviar la respuesta y detener la ejecución

### Métodos Disponibles

```php
// Respuesta JSON
response()->json([
    'success' => true,
    'data' => $user,
    'message' => 'User created'
], 200);

// Respuesta con código de estado
response()->json([
    'success' => false,
    'error' => 'Not found',
    'status' => 404
], 404);

// Respuesta con vista
response()->view('index', ['title' => 'Dashboard']);

// Redirección
response()->redirect('/dashboard');

// Descarga de archivo
response()->download('/path/to/file.pdf');

// Detener ejecución inmediata
response()->exit();
```

### Uso en el Proyecto

**Confirmado desde [`app/Controllers/HomeController.php`](../app/Controllers/HomeController.php)**:
```php
public function index()
{
    return response()->json([
        'message' => 'Welcome to Leaf PHP',
        'version' => '1.0.0'
    ]);
}
```

---

## Headers

### Qué son Headers en Leaf

El objeto Headers en Leaf permite manipular los encabezados HTTP de la respuesta.

### Características Principales

**Confirmado desde el código**:
- **Establecer headers**: `header()`, `set()`, `remove()`
- **Obtener headers**: `get()`, `all()`, `has()`
- **Acceso a headers de solicitud**: Métodos para obtener headers de la solicitud entrante

### Métodos Disponibles

```php
// Establecer un header
response()->header('Content-Type', 'application/json');

// Establecer múltiples headers
response()->headers([
    'Content-Type' => 'application/json',
    'X-Custom-Header' => 'CustomValue'
]);

// Obtener todos los headers
$headers = request()->getHeaders();

// Verificar si existe un header específico
if ($headers->has('Authorization')) {
    $token = $headers->get('Authorization');
}

// Remover un header
response()->remove('X-Custom-Header');
```

### Uso en el Proyecto

**Para CORS**: Establecer headers de CORS para permitir acceso desde el frontend
```php
response()->headers([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
    'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
]);
```

---

## CORS (Cross-Origin Resource Sharing)

### Qué es CORS en Leaf

CORS es un mecanismo de seguridad que permite que una aplicación web en un dominio pueda hacer solicitudes a una API en otro dominio. Leaf tiene un módulo oficial para CORS.

### Características del Módulo CORS en Leaf

**Confirmado desde [`src/App.php`](../src/App.php)**:
```php
/**
 * Evade CORS errors
 *
 * @param $options Config for cors
 */
public function cors($options = [])
{
    if (!class_exists('Leaf\\Cors')) {
        trigger_error('Cors module not found! Run `leaf install cors` or `composer require leafs/cors` to install CORS module. This is required to configure CORS.');
    }
}
```

### Configuración de CORS

```php
// Configuración básica de CORS
app()->cors([
    'origin' => '*',
    'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'headers' => ['Content-Type', 'Authorization']
]);
```

### Uso en el Proyecto

**Para API**: Configurar CORS para permitir acceso desde el frontend
```php
// En bootstrap o configuración
app()->cors([
    'origin' => env('CORS_ORIGIN', '*'),
    'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'headers' => ['Content-Type', 'Authorization', 'X-Requested-With']
]);
```

---

## Guard

### Qué es Guard en Leaf

Guard es un componente de seguridad que se usa para proteger rutas y recursos. Se usa principalmente para autenticación y autorización.

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

### Aplicación a Rutas

```php
// Aplicar middleware de guard a una ruta
app()->get('/protected', [AuthController::class, 'index'])->middleware('auth');

// Aplicar middleware de guard a un grupo
app()->group('/api/v1', ['middleware' => 'auth'], function () {
    app()->get('/users', [UserController::class, 'index']);
});
```

---

## Endpoints API Confirmados

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

### Ejemplos cURL

```bash
# Crear usuario
curl -X POST http://localhost:8000/api/v1/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"secret"}'

# Listar usuarios
curl http://localhost:8000/api/v1/users

# Ver usuario
curl http://localhost:8000/api/v1/users/1
```

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Request object con métodos get(), post(), json() | request-response-headers-cors-guard-leaf.md | ✅ Confirmado |
| Response object con métodos json(), view(), redirect() | request-response-headers-cors-guard-leaf.md | ✅ Confirmado |
| Headers methods header(), set(), remove() | request-response-headers-cors-guard-leaf.md | ✅ Confirmado |
| CORS module available | request-response-headers-cors-guard-leaf.md | ✅ Confirmado |
| AuthMiddleware for route protection | request-response-headers-cors-guard-leaf.md | ✅ Confirmado |
| Endpoints públicos confirmados | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |
| Endpoints de usuarios CRUD confirmados | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |
| Ejemplos cURL para pruebas de API | SETUP_COMPLETE.md | ✅ Confirmado |

---

## Recomendaciones

### Para API

✅ **Usar response()->json()**
- Respuestas JSON para API
- Incluir códigos de estado HTTP apropiados
- Formato consistente de respuestas

### Para CORS

✅ **Configurar CORS apropiadamente**
- Especificar orígenes permitidos
- Especificar métodos HTTP permitidos
- Especificar headers permitidos
- Usar variables de entorno para configuración

### Para Seguridad

✅ **Usar middleware de guard**
- Proteger rutas que requieren autenticación
- Proteger rutas que requieren autorización
- Retornar códigos de estado HTTP apropiados (401, 403)

---

## Referencias a Documentos Fuente

1. **request-response-headers-cors-guard-leaf.md** - Request, Response, Headers, CORS y Guard en Leaf PHP
2. **concepto-de-proyecto.md** - Concepto general del proyecto
3. **SETUP_COMPLETE.md** - Documentación del setup completado con endpoints API
4. **PROJECT_README.md** - README principal del proyecto con endpoints API
