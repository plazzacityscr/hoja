# Índice de Decisiones Arquitectónicas

**Fecha de creación**: 2026-03-25
**Última actualización**: 2026-03-25
**Agente responsable**: `agt-decision-tracker`

---

## Propósito

Este índice proporciona un listado de todos los archivos de decisiones arquitectónicas en `doc_decisiones/`, organizados por tema.

**Nota importante**: Este archivo SOLO contiene el índice de archivos. El contenido detallado de decisiones está en cada archivo temático correspondiente.

---

## Estructura de Archivos

| Archivo | Tema | Descripción |
|---------|------|-------------|
| `index.md` | General | Este índice (listado de archivos) |
| `autenticacion.md` | Autenticación | Decisiones sobre sesiones, JWT, RBAC |
| `modulos.md` | Módulos Leaf | Decisiones sobre módulos y dependencias |
| `despliegue.md` | Despliegue | Decisiones sobre Railway, Docker, variables |
| `feature-toggle.md` | Feature Toggle | Decisiones sobre sistema de activación de funcionalidades |
| `ui.md` | Interfaz de Usuario | Decisiones sobre Gentelella, Blade, componentes |
| `tareas-pendientes.md` | Tareas y Decisiones | Lista consolidada de tareas y decisiones por implementar (incluye decisiones bloqueantes) |

---

## Cómo Usar Este Índice

1. **Identificar archivo por tema**: Buscar en la tabla arriba
2. **Ver detalles**: Abrir archivo temático correspondiente (ej: `autenticacion.md`)
3. **Ver bloqueos y tareas**: Consultar `tareas-pendientes.md` para decisiones que impiden avanzar y tareas por implementar
4. **Solicitar nuevo reporte**: Pedir al orquestador que invoque `agt-decision-tracker`

---

## Flujo de Actualización

```
Usuario solicita rastreo de decisiones
    ↓
Orquestador delega a `agt-decision-tracker`
    ↓
Agente busca en documentación
    ↓
Agente extrae y clasifica decisiones
    ↓
Agente genera/actualiza archivo temático en `doc_decisiones/`
    ↓
Agente actualiza este índice (solo listado de archivos)
```

---

**Nota**: Para ver el estado de decisiones y tareas pendientes, consultar `tareas-pendientes.md` o cada archivo temático.
