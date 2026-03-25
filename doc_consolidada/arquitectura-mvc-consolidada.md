# Arquitectura MVC - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**:
- doc_proyecto/concepto-de-proyecto.md
- doc_respuestas-leaf-docs-researcher/Legado/mvc-modules-scaffolding-explicacion.md
- doc_respuestas-leaf-docs-researcher/Legado/integrar-mvc-y-decision-modulos.md
- SETUP_COMPLETE.md
- PROJECT_README.md

---

## Resumen Ejecutivo

El proyecto sigue una arquitectura **MVC (Model-View-Controller)** que separa la aplicación en tres componentes principales: Models (datos y lógica de negocio), Views (plantillas visuales) y Controllers (conectan modelos y vistas).

**Decisión Confirmada**: Arquitectura MVC para el proyecto.

---

## Conceptualización por MVC

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

### Views

**Papel conceptual**: Representan las plantillas visuales y la interacción de presentación con el usuario.

**Referencia conceptual**: `views/`

**Características conocidas**:
- Basadas en Gentelella (Bootstrap 5 Admin Dashboard Template)
- Incluyen vistas para:
  - Edición de proyectos
  - Visualización de informes
  - Dashboard de administración

**No confirmado**: Estructura específica de vistas, componentes reutilizables, layouts.

### Controllers

**Papel conceptual**: Conectan models y views, manejan la lógica de la aplicación y coordinan las respuestas a las solicitudes del usuario.

**Referencia conceptual**: `app/Controllers/`

**Responsabilidades conceptuales**:
- Recibir solicitudes HTTP
- Validar datos de entrada
- Coordinar operaciones con models
- Seleccionar la vista apropiada
- Retornar respuestas HTTP

**No confirmado**: Estructura específica de controllers, patrones de diseño, manejo de errores.

---

## Estructura de Carpetas MVC

### Confirmado desde el Proyecto

**Estructura MVC implementada** (fuente: SETUP_COMPLETE.md, PROJECT_README.md):

```
app/
├── Controllers/     # Controladores
│   ├── HomeController.php
│   └── UserController.php
├── Middleware/      # Middleware
│   ├── AuthMiddleware.php
│   └── ExampleMiddleware.php
└── Models/          # Modelos
    └── User.php
views/               # Plantillas
    ├── index.view.php
    └── errors/
        ├── 404.view.php
        └── 500.view.php
routes/              # Definición de rutas
    ├── web.php      # Rutas web
    └── api.php      # Rutas API
config/              # Configuraciones
    ├── app.php      # Configuración general
    └── database.php # Configuración de BD
public/              # Raíz web
    └── index.php    # Punto de entrada
```

**Confirmado desde SETUP_COMPLETE.md**:
- ✅ Tipo de aplicación: **Híbrido (API + Views)** - Máxima flexibilidad
- ✅ Entry point configurado en `public/index.php`
- ✅ Rutas separadas: `routes/web.php` y `routes/api.php`
- ✅ CRUD de usuarios completo implementado en `UserController.php`

---

## Cómo Integrar MVC en el Proyecto

### Opción 1: Usar leafmvc (Recomendado para MVC completo)

**Qué hacer**: Instalar el wrapper MVC oficial de Leaf

**Cómo**:
```bash
composer require leafs/mvc
```

**Ventaja**: Proporciona estructura MVC completa pre-configurada.

### Opción 2: Crear estructura MVC manualmente

**Qué hacer**: Crear carpetas y archivos según la estructura del scaffold actual

**Pasos**:

1. **Crear estructura de carpetas**:
   ```
   app/
   ├── Controllers/     # Controladores
   ├── Middleware/      # Middleware
   └── Models/          # Modelos
   views/               # Plantillas
   routes/              # Rutas
   ```

2. **Crear controladores** en `app/Controllers/`:
   - Extienden de un controlador base
   - Manejan lógica de negocio

3. **Crear modelos** en `app/Models/`:
   - Manejan datos y lógica de base de datos
   - Usan `leafs/db`

4. **Crear vistas** en `views/`:
   - Plantillas PHP o Blade
   - Para respuestas HTML

5. **Definir rutas** en `routes/web.php` y `routes/api.php`:
   - Conectan URLs con controladores

---

## Flujo de Datos en MVC

### Flujo Conceptual

```
Solicitud HTTP → Router → Controller → Model → Base de Datos
                                    ↓
                                Datos
                                    ↓
Controller → View → Respuesta HTTP → Cliente
```

### Ejemplo: Mostrar Lista de Proyectos

1. **Solicitud**: Usuario visita `/projects`
2. **Router**: Encuentra ruta `/projects` → `ProjectController::index()`
3. **Controller**: Llama `Project::all()` para obtener proyectos
4. **Model**: Ejecuta query `SELECT * FROM projects` en base de datos
5. **Base de Datos**: Retorna datos de proyectos
6. **Model**: Retorna proyectos al controller
7. **Controller**: Pasa proyectos a la vista `projects/index`
8. **View**: Renderiza HTML con datos de proyectos
9. **Response**: HTML enviado al cliente

---

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Arquitectura MVC para el proyecto | concepto-de-proyecto.md | ✅ Confirmado |
| Models en app/Models/ | concepto-de-proyecto.md, mvc-modules-scaffolding-explicacion.md | ✅ Confirmado |
| Views en views/ | concepto-de-proyecto.md, mvc-modules-scaffolding-explicacion.md | ✅ Confirmado |
| Controllers en app/Controllers/ | concepto-de-proyecto.md, mvc-modules-scaffolding-explicacion.md | ✅ Confirmado |
| Estructura de carpetas MVC | concepto-de-proyecto.md, mvc-modules-scaffolding-explicacion.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| leafmvc como wrapper MVC | mvc-modules-scaffolding-explicacion.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| Tipo de aplicación: Híbrido (API + Views) | SETUP_COMPLETE.md | ✅ Confirmado |
| Entry point en public/index.php | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |
| Rutas separadas web.php y api.php | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |
| CRUD de usuarios completo implementado | SETUP_COMPLETE.md | ✅ Confirmado |

---

## Recomendaciones

### Para Implementación

✅ **Seguir convenciones de MVC**
- Separar claramente models, views y controllers
- Mantener lógica de negocio en models
- Mantener lógica de presentación en views
- Mantener coordinación en controllers

### Para Organización

✅ **Organizar por recurso**
- `ProjectController`, `ProjectModel`, `views/projects/`
- `UserController`, `UserModel`, `views/users/`

### Para Mantenibilidad

✅ **Usar patrones de diseño apropiados**
- Repository pattern para acceso a datos
- Service pattern para lógica de negocio compleja
- View composers para datos compartidos en vistas

---

## Referencias a Documentos Fuente

1. **concepto-de-proyecto.md** - Concepto general del proyecto con arquitectura MVC
2. **mvc-modules-scaffolding-explicacion.md** - MVC, Modules y App Scaffolding en Leaf PHP
3. **integrar-mvc-y-decision-modulos.md** - Integrar MVC en Leaf y decidir módulos necesarios
4. **SETUP_COMPLETE.md** - Documentación del setup completado con estructura MVC implementada
5. **PROJECT_README.md** - README principal del proyecto con estructura MVC
