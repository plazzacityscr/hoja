# Agentes Disponibles

## Resumen

| Campo | Valor |
|---|---|
| total_agentes | 12 |
| fecha_actualizacion | 2026-03-25 |

## Agentes

| Especialidad | Nombre | Ruta | Tipo | Descripción | Estado |
|---|---|---|---|---|---|
| Raíz | inventariador | `.claude/agents/inventariador.md` | raíz | Agente especializado en la gestión, normalización y actualización exclusiva del inventario de recursos y configuración operativa del proyecto | activo |
| Ejecutores - Documentación | leaf-docs-researcher | `.claude/agents/ejecutores/leaf-docs-researcher.md` | ejecutor | Research and answer questions about Leaf PHP framework using official documentation as the primary source. Provides clear explanations for beginners and can inspect projects for Leaf readiness. | activo |
| Raíz | orquestador | `.claude/agents/orquestador.md` | raíz | no especificada | activo |
| Ejecutores - Documentación | agt-doc-consolidator | `.claude/agents/ejecutores/agt-doc-consolidator.md` | ejecutor | Consolidate fragmented information from multiple documents into unified and coherent views. Detect and resolve duplicate or contradictory information while maintaining traceability to source documents. | activo |
| Ejecutores - Documentación | agt-doc-researcher | `.claude/agents/ejecutores/agt-doc-researcher.md` | ejecutor | Investigar y extraer información específica de la documentación interna del proyecto. Búsqueda dirigida por queries con clasificación de hechos verificables vs inferencias. | activo |
| Ejecutores - Documentación | agt-decision-tracker | `.claude/agents/ejecutores/agt-decision-tracker.md` | ejecutor | Rastrear, catalogar y dar seguimiento a decisiones arquitectónicas (confirmadas, pendientes y descartadas). Extrae decisiones de documentos, identifica dependencias y genera reportes de estado en doc_decisiones/. | activo |
| Ejecutores - Feature Toggle | agt-feature-toggle-designer | `.claude/agents/ejecutores/agt-feature-toggle-designer.md` | ejecutor | Diseñar y validar estructura del sistema de Feature Toggle (tablas, middleware, integración con routing y RBAC). | activo |
| Ejecutores - Core | agt-core-routing | `.claude/agents/ejecutores/agt-core-routing.md` | ejecutor | Traducir requisitos funcionales en estructura de routing (rutas, grupos, middleware) para recursos core de la aplicación. | activo |
| Ejecutores - Core | agt-core-request-response | `.claude/agents/ejecutores/agt-core-request-response.md` | ejecutor | Diseñar y validar patrones de request/response HTTP, formatos de respuesta, headers y configuración CORS para asegurar coherencia con R6 y R7. | activo |
| Ejecutores - Core | agt-core-config-deployment | `.claude/agents/ejecutores/agt-core-config-deployment.md` | ejecutor | Diseñar y validar configuración, variables de entorno, despliegue en Railway, manejo de errores, DI, Docker, testing y linting para asegurar coherencia con R2, R3, R8, R8.b, R10, R11. | activo |
| Ejecutores - Core | agt-railway-deploy-agent | `.claude/agents/ejecutores/agt-railway-deploy-agent.md` | ejecutor | Validar, preparar y ejecutar despliegues a Railway conforme a la gobernanza del proyecto, sin inventar configuración ni asumir capacidades no documentadas. | activo |
| Ejecutores - Core | agt-leaf-test-runner | `.claude/agents/ejecutores/agt-leaf-test-runner.md` | ejecutor | Ejecutar tests y checks reales del proyecto Leaf cuando sea invocado, validando calidad y preparación básica. | activo |

## Notas

| Nota |
|---|
| Este listado es generado automáticamente. |
| No editar manualmente salvo indicación explícita. |