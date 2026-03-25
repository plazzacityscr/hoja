# Plan de Implementación FASE 1 - Sistema Doc-First

**Fecha**: 2026-03-25
**Fase**: FASE 1 - Autenticación, CRUD Usuarios y UI Base
**Duración**: 5 sprints semanales
**Documento base**: `plan-implementacion-fase-1-qwen.md`

---

## Índice de Contenido

1. [Resumen de la Fase](#1-resumen-de-la-fase)
2. [Sprint 1 - Infraestructura Base](#2-sprint-1---infraestructura-base)
3. [Sprint 2 - Autenticación Backend](#3-sprint-2---autenticación-backend)
4. [Sprint 3 - Autenticación UI](#4-sprint-3---autenticación-ui)
5. [Sprint 4 - CRUD Usuarios Backend](#5-sprint-4---crud-usuarios-backend)
6. [Sprint 5 - CRUD Usuarios UI y Cierre](#6-sprint-5---crud-usuarios-ui-y-cierre)
7. [Dependencias entre Sprints](#7-dependencias-entre-sprints)

---

## 1. Resumen de la Fase

### Objetivos de FASE 1

- Endpoints API de autenticación funcionales
- CRUD de usuarios con RBAC completo
- UI con dashboard, login y formularios
- Integración backend-frontend validada

### Tareas Incluidas

| Área | Tareas |
|------|--------|
| **API** | Auth (login, logout), CRUD usuarios |
| **Backend** | Redis, sesiones, RBAC, middleware |
| **UI** | Blade, Gentelella, layout, formularios |

### Tareas Excluidas (FASE 2)

- UI-T004 (Vistas CRUD proyectos)
- Feature Toggle (FT-T001 a FT-T005)
- CRUD proyectos (API-T002)
- OpenAI integration (API-T003, T004)

---

## 2. Sprint 1 - Infraestructura Base

**Duración**: Semana 1
**Objetivo**: Establecer infraestructura técnica para autenticación y UI

### Tareas Previstas

| ID | Tarea | Descripción |
|----|-------|-------------|
| S1-T01 | Configurar Redis en Railway | Crear servicio Redis en Railway para sesiones y caché |
| S1-T02 | Configurar session handler en PHP | Actualizar configuración PHP para usar Redis como almacén de sesiones |
| S1-T03 | Instalar Blade | Instalar motor de vistas Blade vía Composer y configurar paths |
| S1-T04 | Integrar Gentelella | Descargar y configurar plantilla Gentelella en estructura de vistas |

### Entregables del Sprint

- Redis operativo en Railway y local
- Sesiones PHP almacenadas en Redis
- Blade configurado y funcional
- Gentelella disponible en `views/`

---

## 3. Sprint 2 - Autenticación Backend

**Duración**: Semana 2
**Objetivo**: Implementar endpoints de autenticación y RBAC

### Tareas Previstas

| ID | Tarea | Descripción |
|----|-------|-------------|
| S2-T01 | Diseñar esquema de usuarios y roles | Definir estructura de tablas users, roles, permissions en base de datos |
| S2-T02 | Crear migraciones de autenticación | Generar archivos de migración para tablas de autenticación y RBAC |
| S2-T03 | Implementar modelo User | Crear modelo User con métodos de autenticación y relación con roles |
| S2-T04 | Implementar modelo Role | Crear modelo Role con relación a usuarios y permisos |
| S2-T05 | Endpoint POST /api/v1/auth/login | Crear endpoint que valide credenciales y genere sesión |
| S2-T06 | Endpoint POST /api/v1/auth/logout | Crear endpoint que destruya sesión de usuario |
| S2-T07 | Implementar RBAC backend | Crear lógica de verificación de roles y permisos |
| S2-T08 | Middleware de autenticación | Crear middleware que verifique sesión activa en rutas protegidas |
| S2-T09 | Middleware de RBAC | Crear middleware que verifique permisos por rol |

### Entregables del Sprint

- Tablas users, roles, permissions creadas
- Modelos User y Role funcionales
- Endpoints login/logout operativos
- Middleware de auth y RBAC implementados
- Endpoints documentados (Postman/Insomnia)

---

## 4. Sprint 3 - Autenticación UI

**Duración**: Semana 3
**Objetivo**: Implementar interfaz de autenticación y dashboard base

### Tareas Previstas

| ID | Tarea | Descripción |
|----|-------|-------------|
| S3-T01 | Diseñar layout base de dashboard | Crear estructura de layout principal con sidebar, navbar y contenido |
| S3-T02 | Implementar sidebar con secciones | Configurar menú lateral organizado por secciones de la aplicación |
| S3-T03 | Diseñar vista de login | Crear vista `auth/login.blade.php` con formulario de credenciales |
| S3-T04 | Implementar formulario de login | Conectar formulario con endpoint de login y manejar respuesta |
| S3-T05 | Implementar logout en navbar | Añadir botón/enlace de logout en navbar del dashboard |
| S3-T06 | Sistema de mensajes flash | Crear componente de alertas para mensajes de éxito/error |
| S3-T07 | Mostrar usuario en navbar | Implementar visualización de nombre y rol de usuario autenticado |
| S3-T08 | Proteger rutas del dashboard | Configurar redirección a login para rutas sin autenticación |

### Entregables del Sprint

- Layout de dashboard con sidebar funcional
- Vista de login con formulario operativo
- Logout funcional desde navbar
- Mensajes flash para feedback de usuario
- Usuario y rol visibles en navbar
- Rutas protegidas validan autenticación

---

## 5. Sprint 4 - CRUD Usuarios Backend

**Duración**: Semana 4
**Objetivo**: Implementar backend completo de gestión de usuarios

### Tareas Previstas

| ID | Tarea | Descripción |
|----|-------|-------------|
| S4-T01 | Diseñar endpoints CRUD usuarios | Definir estructura de endpoints para operaciones CRUD de usuarios |
| S4-T02 | Endpoint GET /api/v1/users | Implementar listado de usuarios con paginación y filtros |
| S4-T03 | Endpoint POST /api/v1/users | Implementar creación de usuario con validación y asignación de rol |
| S4-T04 | Endpoint GET /api/v1/users/{id} | Implementar obtención de detalle de usuario |
| S4-T05 | Endpoint PUT /api/v1/users/{id} | Implementar actualización de usuario y cambio de rol |
| S4-T06 | Endpoint DELETE /api/v1/users/{id} | Implementar eliminación de usuario con validación de permisos |
| S4-T07 | Validaciones de formularios backend | Implementar validación de datos de entrada en todos los endpoints |
| S4-T08 | Permisos por rol en endpoints | Configurar qué roles pueden ejecutar cada operación del CRUD |
| S4-T09 | Seeders de roles y usuarios | Crear datos iniciales de roles y usuario admin por defecto |

### Entregables del Sprint

- 5 endpoints CRUD de usuarios operativos
- Validaciones de datos implementadas
- Permisos RBAC configurados por endpoint
- Roles iniciales creados (admin, user)
- Usuario admin por defecto disponible
- Endpoints documentados y probados

---

## 6. Sprint 5 - CRUD Usuarios UI y Cierre

**Duración**: Semana 5
**Objetivo**: Implementar interfaz de CRUD y estabilizar FASE 1

### Tareas Previstas

| ID | Tarea | Descripción |
|----|-------|-------------|
| S5-T01 | Diseñar listado de usuarios en dashboard | Crear vista de tabla con usuarios, roles y acciones disponibles |
| S5-T02 | Implementar formulario crear usuario | Crear modal/vista para creación de usuario con selección de rol |
| S5-T03 | Implementar formulario editar usuario | Crear modal/vista para edición de usuario y cambio de rol |
| S5-T04 | Implementar confirmación de eliminación | Crear modal de confirmación antes de eliminar usuario |
| S5-T05 | Elementos UI condicionales por rol | Implementar mostrar/ocultar botones según permisos del usuario |
| S5-T06 | Integrar CRUD con endpoints | Conectar todos los formularios con endpoints del backend |
| S5-T07 | Pruebas de integración end-to-end | Ejecutar pruebas completas de autenticación y CRUD |
| S5-T08 | Corrección de bugs críticos | Resolver errores encontrados en pruebas de integración |
| S5-T09 | Deploy de estabilización | Desplegar versión estable de FASE 1 en Railway |

### Entregables del Sprint

- Listado de usuarios visible en dashboard
- Formularios de crear/editar usuario operativos
- Eliminación de usuarios con confirmación
- Elementos de UI se muestran/ocultan por rol
- CRUD completamente integrado con backend
- FASE 1 probada end-to-end
- FASE 1 desplegada y estable en producción

---

## 7. Dependencias entre Sprints

```
Sprint 1 (Infraestructura)
    │
    ├── Redis ──────────────────────────┐
    ├── Blade ──────────────────────┐   │
    └── Gentelella ─────────────┐   │   │
                                │   │   │
                                ▼   ▼   ▼
Sprint 2 (Auth Backend)         │   │   │
    │                           │   │   │
    ├── Endpoints auth ─────────┘   │   │
    ├── RBAC backend ───────────────┘   │
    └── Middleware ─────────────────────┘
    │
    ▼
Sprint 3 (Auth UI)
    │
    ├── Layout dashboard ───────────────┐
    ├── Login form ─────────────────────┤
    └── Navbar con usuario ─────────────┤
    │                                   │
    ▼                                   │
Sprint 4 (CRUD Backend)                 │
    │                                   │
    ├── Endpoints CRUD users ───────────┤
    ├── Validaciones ───────────────────┤
    └── Permisos por rol ───────────────┤
    │                                   │
    ▼                                   ▼
Sprint 5 (CRUD UI y Cierre) ◄───────────┘
    │
    ├── Listado usuarios
    ├── Formularios CRUD
    ├── Elementos por rol
    └── Pruebas y deploy
```

### Dependencias Críticas

| Sprint | Depende de | Razón |
|--------|------------|-------|
| Sprint 2 | Sprint 1 | Redis necesario para sesiones |
| Sprint 3 | Sprint 2 | Endpoints auth necesarios para login UI |
| Sprint 4 | Sprint 2 | RBAC backend necesario antes de CRUD |
| Sprint 5 | Sprint 3 y 4 | Layout y endpoints necesarios para CRUD UI |

---

## Resumen de Entregables por Sprint

| Sprint | Entregables Principales |
|--------|------------------------|
| **S1** | Redis, Blade, Gentelella |
| **S2** | Endpoints auth, RBAC, middleware |
| **S3** | Layout, login, navbar, logout |
| **S4** | CRUD endpoints, validaciones, permisos |
| **S5** | CRUD UI, integración, deploy estable |

---

**Nota**: Este plan asume que el despliegue en Railway ya está configurado y funcional (DEPLOY-001, DEPLOY-002 confirmadas). Las tareas de Redis son para añadir el servicio de caché/sesiones, no para crear el proyecto en Railway.
