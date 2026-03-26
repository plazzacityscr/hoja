# FASE 1 - Análisis de Dependencias Backend-Frontend

**Fecha**: 2026-03-25
**Propósito**: Documentar dependencias entre UI/Templates y Endpoints API/Autenticación para FASE 1

---

## Índice de Contenido

1. [Impacto de UI/Templates en Endpoints API](#1-impacto-de-uitemplates-en-endpoints-api)
2. [Impacto de UI/Templates en Autenticación](#2-impacto-de-uitemplates-en-autenticación)
3. [Dependencias Prácticas entre Backend y Frontend](#3-dependencias-prácticas-entre-backend-y-frontend)
4. [Orden de Ejecución Recomendado](#4-orden-de-ejecución-recomendado)

---

## 1. Impacto de UI/Templates en Endpoints API

Los endpoints API **no pueden validarse completamente** sin UI por las siguientes razones:

| Endpoint | Requiere UI | Razón Concreta |
|----------|-------------|----------------|
| `POST /api/v1/auth/login` | ✅ Sí | Sin formulario no se prueba el flujo completo |
| `POST /api/v1/auth/logout` | ✅ Sí | Sin botón no se verifica destrucción de sesión |
| `GET /api/v1/users` | ✅ Sí | Sin vista no se valida listado con RBAC |
| `POST /api/v1/users` | ✅ Sí | Sin formulario no se crea usuario desde UI |
| `PUT /api/v1/users/{id}` | ✅ Sí | Sin formulario no se edita usuario desde UI |
| `DELETE /api/v1/users/{id}` | ✅ Sí | Sin botón no se elimina usuario desde UI |

**Conclusión**: Endpoints API requieren formularios HTML y vistas para validación end-to-end.

---

## 2. Impacto de UI/Templates en Autenticación

La autenticación **requiere UI obligatoriamente** por:

| Componente | Requiere UI | Razón Concreta |
|------------|-------------|----------------|
| Login | ✅ Sí | Formulario email/password |
| Logout | ✅ Sí | Botón en navbar |
| Sesión activa | ✅ Sí | Mostrar usuario y rol en navbar |
| Protección de rutas | ✅ Sí | Redirección a login + vista |
| RBAC | ✅ Sí | Mostrar/ocultar elementos por rol |
| Mensajes de error | ✅ Sí | Alertas de credenciales/permisos |

**Conclusión**: Autenticación requiere `views/auth/login.blade.php`, `layouts/dashboard.blade.php`, `components/alerts.blade.php`.

---

## 3. Dependencias Prácticas entre Backend y Frontend

```
AUTH-C001 (Redis) → AUTH-C002 (Session handler) → AUTH-T001 (Login BE)
                                                              ↓
UI-T001 (Blade) → UI-T002 (Gentelella) → UI-T003 (Layout) → AUTH-T002 (Logout)
                                                              ↓
                                              AUTH-T004 (RBAC) → API-T002 (CRUD Users)
```

**Dependencias críticas**:
1. Redis antes que Login
2. Blade antes que cualquier vista
3. Gentelella antes que vistas específicas
4. RBAC antes que CRUD de usuarios
5. Layout antes que formularios CRUD

---

## 4. Orden de Ejecución Recomendado

### Semana 1: Infraestructura Base
- AUTH-C001: Configurar Redis
- AUTH-C002: Configurar session handler
- UI-T001: Instalar Blade
- UI-T002: Integrar Gentelella

### Semana 2: Autenticación
- AUTH-T001: Login endpoint + vista login
- AUTH-T002: Logout endpoint + botón logout
- UI-T003 (parte 1-4): Layout, login, mensajes, navbar

### Semana 3: CRUD Usuarios con RBAC
- API-T002: CRUD endpoints
- AUTH-T004: RBAC backend
- UI-T003 (parte 5-8): Listado, formularios, elementos RBAC

### Semana 4: Estabilización
- Pruebas end-to-end
- Deploy de estabilización

---

## Tareas Excluidas de FASE 1

- UI-T004 (Vistas CRUD proyectos) → FASE 2
- FT-T001 a FT-T005 (Feature Toggle) → FASE 2
- API-T002 (CRUD proyectos) → FASE 2
- API-T003, API-T004 (OpenAI) → FASE 2
