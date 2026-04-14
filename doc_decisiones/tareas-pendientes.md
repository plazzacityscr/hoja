# Tareas Pendientes y Decisiones por Implementar

**Fecha de creación**: 2026-03-25
**Última actualización**: 2026-03-25
**Tipo**: Lista consolidada de tareas y decisiones pendientes

---

## Índice de Contenido

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Tareas Pendientes por Área](#tareas-pendientes-por-área)
3. [Decisiones Pendientes](#decisiones-pendientes)
4. [Decisiones Recientemente Confirmadas](#decisiones-recientemente-confirmadas)
5. [Dependencias entre Tareas](#dependencias-entre-tareas)
6. [Plan de Implementación - FASE 1](#plan-de-implementación-fase-1)

---

## Resumen Ejecutivo

Este archivo consolida todas las tareas pendientes y decisiones por implementar del proyecto Hoja. Las decisiones arquitectónicas ya están documentadas en archivos temáticos de `doc_decisiones/`. Este archivo solo lista lo que falta por hacer.

**Estado general**:
- ✅ Decisiones arquitectónicas principales tomadas
- ⏳ Implementación de MVP en curso
- ⚠️ 7 tareas críticas identificadas
- ✅ No hay decisiones bloqueantes

---

## Tareas Pendientes por Área

### Autenticación

| ID | Tarea | Prioridad | Estado | Notas |
|----|-------|-----------|--------|-------|
| AUTH-T001 | Implementar login de usuario | ALTA | ⏳ Pendiente | Requiere Redis configurado |
| AUTH-T002 | Implementar logout de usuario | ALTA | ⏳ Pendiente | - |
| AUTH-T003 | Implementar registro de usuario | MEDIA | ⏳ Pendiente | - |
| AUTH-T004 | Implementar RBAC (roles y permisos) | ALTA | ⏳ Pendiente | Requiere Redis configurado |

**Decisión relacionada**: AUTH-002 (Almacenamiento de sesiones en Redis) - CONFIRMADA

**Tareas de configuración**:
| ID | Tarea | Prioridad | Estado | Notas |
|----|-------|-----------|--------|-------|
| AUTH-C001 | Configurar servicio Redis en Railway | ALTA | ⏳ Pendiente | Compartido con feature toggles |
| AUTH-C002 | Configurar session handler en PHP | ALTA | ⏳ Pendiente | Actualizar `config/app.php` |

---

### Despliegue

**Estado**: ✅ Creado en Railway con despliegue funcional

**Configuración implementada**:
- ✅ `railway.json` - Configuración de Railway con Dockerfile
- ✅ `Dockerfile` - Imagen de producción optimizada
- ✅ `Procfile` - Fallback para Railway/Heroku
- ✅ Variables de entorno documentadas en `.env.example`
- ✅ Decisiones arquitectónicas confirmadas (DEPLOY-001, DEPLOY-002)
- ✅ Proyecto creado en Railway con despliegue funcional
- ✅ Base de datos PostgreSQL creada y conectada
- ✅ Variables de entorno configuradas en Railway

**Decisiones relacionadas**: 
- DEPLOY-001 (Railway como plataforma de despliegue) - CONFIRMADA
- DEPLOY-002 (Método de despliegue automático desde GitHub) - CONFIRMADA

**Estado actual verificado**:
- El proyecto ya está creado en Railway
- El despliegue inicial es funcional
- La base de datos PostgreSQL está creada y conectada
- Las variables de entorno están configuradas en Railway
- El método de despliegue automático desde GitHub está operativo

**Tareas pendientes de despliegue**:

| ID | Tarea | Prioridad | Estado | Notas |
|----|-------|-----------|--------|-------|
| DEPLOY-T004 | Configurar dominio personalizado (opcional) | BAJA | ⏳ Opcional | Dominio personalizado vs subdomain Railway |

**Método de despliegue actual**:
1. Push de código a GitHub
2. Railway detecta cambios automáticamente
3. Railway construye imagen usando `Dockerfile`
4. Despliegue automático con SSL gratuito
5. Rollback disponible desde el dashboard de Railway

**Referencias**:
- `.governance/inventario_recursos.md` - Sección 0: Método de Despliegue Activo
- `doc_decisiones/despliegue.md` - Decisiones DEPLOY-001 y DEPLOY-002 CONFIRMADAS
- `SETUP_COMPLETE.md` - Deploy a Railway: Preparado
- `PROJECT_README.md` - Instrucciones de despliegue en Railway

---

### Feature Toggle

| ID | Tarea | Prioridad | Estado | Notas |
|----|-------|-----------|--------|-------|
| FT-T001 | Diseñar estructura de datos para feature toggles | ALTA | ⏳ Pendiente | Responsable: `agt-feature-toggle-designer` |
| FT-T002 | Crear migraciones para feature_toggles | ALTA | ⏳ Pendiente | Incluir parent_id para submódulos |
| FT-T003 | Implementar ModuleActiveMiddleware | MEDIA | ⏳ Pendiente | - |
| FT-T004 | Implementar ModuleAccessMiddleware | MEDIA | ⏳ Pendiente | - |
| FT-T005 | Implementar caché en Redis para feature toggles | MEDIA | ⏳ Pendiente | Compartir servicio Redis con sesiones |

**Decisiones relacionadas**: 
- FT-001 (Sistema de Feature Toggle) - CONFIRMADA
- FT-002 (Estrategia de caché en Redis) - CONFIRMADA
- FT-003 (Nivel de granularidad: módulos y submódulos) - CONFIRMADA

---

### UI/Templates

| ID | Tarea | Prioridad | Estado | Notas |
|----|-------|-----------|--------|-------|
| UI-T001 | Instalar Blade como motor de vistas | ALTA | ⏳ Pendiente | UI-002 confirmada |
| UI-T002 | Configurar integración con Gentelella | ALTA | ⏳ Pendiente | - |
| UI-T003 | Crear layout base para dashboard | ALTA | ⏳ Pendiente | - |
| UI-T004 | Crear vistas para CRUD de proyectos | ALTA | ⏳ Pendiente | - |

**Decisión relacionada**: UI-002 (Blade como motor de vistas) - Ver `ui.md`

---

### Módulos/Dependencias

| ID | Tarea | Prioridad | Estado | Notas |
|----|-------|-----------|--------|-------|
| MOD-T001 | Definir framework de testing | MEDIA | ⏳ Pendiente | MOD-002 pendiente |
| MOD-T002 | Configurar herramientas de linting | BAJA | ⏳ Pendiente | - |
| MOD-T003 | Evaluar sistema de colas para workflows | BAJA | ⏳ Pendiente | Fase posterior |

**Decisión relacionada**: MOD-002 (Sistema de colas) - Ver `modulos.md`

---

### Endpoints API

| ID | Tarea | Prioridad | Estado | Notas |
|----|-------|-----------|--------|-------|
| API-T001 | Implementar endpoints de autenticación | ALTA | ⏳ Pendiente | Login, logout, register |
| API-T002 | Implementar CRUD de proyectos | ALTA | ⏳ Pendiente | - |
| API-T003 | Implementar endpoint de análisis con OpenAI | ALTA | ⏳ Pendiente | Requiere API key |
| API-T004 | Configurar OpenAI API integration | ALTA | ⏳ Pendiente | Secret pendiente |

---

## Decisiones Pendientes

| ID | Decisión | Tema | Impacto | Bloquea | Archivo |
|----|----------|------|---------|---------|---------|
| MOD-002 | Sistema de colas (fase posterior) | Módulos | BAJO | Ninguna | `modulos.md` |

**Nota**: No hay decisiones bloqueantes en este momento.

---

## Decisiones Recientemente Confirmadas

| ID | Decisión | Tema | Fecha de Confirmación | Archivo |
|----|----------|------|---------------------|---------|
| AUTH-002 | Almacenamiento de sesiones en Redis | Autenticación | 2026-03-25 | `autenticacion.md` |
| FT-002 | Estrategia de caché para feature toggles en Redis | Feature Toggle | 2026-03-25 | `feature-toggle.md` |
| FT-003 | Nivel de granularidad (módulos y submódulos) | Feature Toggle | 2026-03-25 | `feature-toggle.md` |

---

## Dependencias entre Tareas

```
AUTH-C001 (Configurar Redis en Railway)
    ├── AUTH-T001 (Login)
    ├── AUTH-T004 (RBAC)
    └── FT-T005 (Caché feature toggles)

AUTH-C002 (Configurar session handler)
    ├── AUTH-T001 (Login)
    └── AUTH-T002 (Logout)

FT-001 (Feature Toggle structure)
    ├── FT-T001 (Diseñar estructura)
    ├── FT-T002 (Migraciones)
    ├── FT-T003 (ModuleActiveMiddleware)
    └── FT-T004 (ModuleAccessMiddleware)

UI-002 (Blade installation)
    ├── UI-T001 (Instalar Blade)
    ├── UI-T002 (Configurar Gentelella)
    └── UI-T003 (Crear layout)

API-T003 (OpenAI endpoint)
    └── API-T004 (Configurar OpenAI API)
```

**Nota**: AUTH-002 (Almacenamiento de sesiones en Redis), FT-002 (Caché en Redis) y FT-003 (Granularidad: módulos y submódulos) están CONFIRMADAS. El servicio Redis se compartirá entre sesiones y caché de feature toggles.

---

## Plan de Implementación - FASE 1

**Documento de referencia completo**: [`doc_revisiones/plan-implementacion-fase-1-qwen.md`](../doc_revisiones/plan-implementacion-fase-1-qwen.md)

### Resumen de FASE 1

**Objetivo**: Establecer endpoints API de autenticación, CRUD de usuarios con RBAC, y UI base funcional.

**Duración estimada**: 4-5 semanas

**Nota importante**: Este plan reconoce las **dependencias cruzadas entre backend y frontend**. No es una lista lineal de tareas.

---

### Fase 1.1: Infraestructura Base (Semana 1)

| Tarea | Responsable | Dependencias |
|-------|-------------|--------------|
| AUTH-C001: Configurar Redis en Railway | Equipo de desarrollo | Cuenta de Railway |
| AUTH-C002: Configurar session handler en PHP | Equipo de desarrollo | AUTH-C001 |
| UI-T001: Instalar Blade | Equipo de desarrollo | - |
| UI-T002: Integrar Gentelella | Equipo de desarrollo | UI-T001 |

**Entregables**: Redis funcionando, sesiones en Redis, Blade configurado, Gentelella integrado

---

### Fase 1.2: Autenticación Completa (Semana 2)

| Tarea | Responsable | Dependencias |
|-------|-------------|--------------|
| AUTH-T001: Endpoint POST /api/v1/auth/login | Equipo de desarrollo | AUTH-C002 |
| AUTH-T002: Endpoint POST /api/v1/auth/logout | Equipo de desarrollo | AUTH-T001 |
| AUTH-T004 (BE): RBAC - Verificación de roles | Equipo de desarrollo | AUTH-T001 |
| UI-T003 (parte 1-4): Layout, login, mensajes flash, navbar | Equipo de desarrollo | UI-T002, AUTH-T001 |

**Entregables**: Login funcional, logout funcional, RBAC básico, dashboard con navbar

---

### Fase 1.3: CRUD de Usuarios con RBAC (Semana 3)

| Tarea | Responsable | Dependencias |
|-------|-------------|--------------|
| API-T002: CRUD de usuarios (backend) | Equipo de desarrollo | Fase 1.2 |
| AUTH-T004 (BE): Permisos por rol | Equipo de desarrollo | API-T002 |
| UI-T003 (parte 5-8): Listado, formularios CRUD, elementos RBAC | Equipo de desarrollo | API-T002, AUTH-T004 |

**Entregables**: CRUD usuarios completo, gestión de roles, permisos visuales en UI

---

### Fase 1.4: Estabilización y Pruebas (Semana 4)

| Tarea | Responsable | Dependencias |
|-------|-------------|--------------|
| API-T001: Pruebas de integración de auth | Equipo de desarrollo | Fase 1.3 |
| API-T002: Pruebas de CRUD de usuarios | Equipo de desarrollo | Fase 1.3 |
| AUTH-T004: Pruebas de RBAC | Equipo de desarrollo | Fase 1.3 |
| Deploy de estabilización | Equipo de desarrollo | Pruebas completadas |

**Entregables**: FASE 1 probada end-to-end y estable en producción

---

### Tareas excluidas de FASE 1 (FASE 2)

- UI-T004 (Vistas CRUD de proyectos)
- FT-T001 a FT-T005 (Feature Toggle)
- API-T002 (CRUD proyectos)
- API-T003, API-T004 (OpenAI integration)
- MOD-T001 a T003 (Módulos/testing/linting)

---

### Criterios de Aceptación de FASE 1

**Funcionales**:
- [ ] F1: Usuario puede iniciar sesión con email/password
- [ ] F2: Usuario puede cerrar sesión
- [ ] F3: Usuario autenticado ve su nombre y rol en navbar
- [ ] F4-F7: CRUD de usuarios con RBAC funcional
- [ ] F8-F10: Permisos validados en backend y UI

**Técnicos**:
- [ ] T1: Blade configurado y funcionando
- [ ] T2: Gentelella integrado como UI
- [ ] T3-T4: Middleware de autenticación y RBAC implementados
- [ ] T5: Endpoints API documentados

---

## Referencias a Archivos de Decisiones

| Archivo | Tema | Decisiones Contenidas |
|---------|------|----------------------|
| `autenticacion.md` | Autenticación | AUTH-001, AUTH-002 (CONFIRMADAS), AUTH-003 |
| `despliegue.md` | Despliegue | DEPLOY-001, DEPLOY-002 (CONFIRMADAS) |
| `feature-toggle.md` | Feature Toggle | FT-001, FT-002 (CONFIRMADAS), FT-003 (CONFIRMADA) |
| `ui.md` | UI | UI-001, UI-002 |
| `modulos.md` | Módulos | MOD-001, MOD-002 |

**Actualización**: AUTH-002, FT-002 y FT-003 fueron confirmadas el 2026-03-25. No hay decisiones bloqueantes.

---

**Nota**: Este archivo consolida la información de tareas pendientes y decisiones. Para ver el detalle de cada decisión, consultar los archivos temáticos en `doc_decisiones/`.
