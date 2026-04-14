# Índice de Vistas Consolidadas

**Fecha**: 2026-03-25
**Agente**: agt-doc-consolidator

---

## Resumen

Este directorio contiene vistas consolidadas de la documentación fragmentada del proyecto. Cada vista consolida información de múltiples documentos fuente en una visión unificada y coherente, manteniendo trazabilidad a los documentos originales.

## Vistas Consolidadas

### 1. Autenticación
**Archivo**: [`autenticacion-consolidada.md`](autenticacion-consolidada.md)

**Temas cubiertos**:
- Comparación Sesiones vs JWT
- RBAC (Role-Based Access Control)
- Middleware de autenticación
- Responsabilidades de Leaf vs Desarrollador
- Flujo conceptual de autenticación

**Decisiones confirmadas**:
- Sesiones + RBAC para MVP

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/Legado/sistema-autenticacion-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md
- doc_respuestas-leaf-docs-researcher/Legado/validacion-enfoque-jwt-rbac-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/validacion-enfoque-sesiones-rbac-leaf.md
- doc_proyecto/concepto-de-proyecto.md

---

### 2. Despliegue
**Archivo**: [`despliegue-consolidada.md`](despliegue-consolidada.md)

**Temas cubiertos**:
- Plataformas de despliegue (Railway, Docker, VPS)
- Configuración de Railway
- Variables de entorno
- Checklist de despliegue
- Consideraciones de escalabilidad

**Decisiones confirmadas**:
- Railway como plataforma de despliegue

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/config-deployment-error-handling-di-docker-testing-guard-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/analisis-requisitos-proyecto-inmobiliario.md
- doc_proyecto/concepto-de-proyecto.md

---

### 3. Feature Toggle
**Archivo**: [`feature-toggle-consolidada.md`](feature-toggle-consolidada.md)

**Temas cubiertos**:
- Distinción: funcionalidades de aplicación vs módulos de Leaf
- Compatibilidad con la filosofía de Leaf
- Mecanismos de Leaf para implementar feature toggle
- Middleware para verificar módulos activos
- Rutas condicionales por módulo

**Decisiones confirmadas**:
- Sistema de Feature Toggle para funcionalidades de aplicación

**Documentos fuente**:
- doc_consolidada/evaluacion-feature-toggle-funcionalidades-app-leaf.md
- doc_proyecto/concepto-de-proyecto.md
- doc_respuestas-leaf-docs-researcher/rc_analisis-estrategico-agentes-multiagente.md

---

### 4. Módulos Leaf
**Archivo**: [`modulos-leaf-consolidada.md`](modulos-leaf-consolidada.md)

**Temas cubiertos**:
- Qué son los módulos de Leaf
- Módulos principales (leafs/leaf, leafs/http, leafs/db, etc.)
- MVC y App Scaffolding
- Cómo integrar MVC en el proyecto
- Qué módulos son necesarios según requisitos

**Decisiones confirmadas**:
- Uso de módulos específicos según necesidades del proyecto

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/Legado/mvc-modules-scaffolding-explicacion.md
- doc_respuestas-leaf-docs-researcher/Legado/integrar-mvc-y-decision-modulos.md
- doc_respuestas-leaf-docs-researcher/Legado/analisis-requisitos-proyecto-inmobiliario.md
- doc_proyecto/concepto-de-proyecto.md

---

### 5. Routing
**Archivo**: [`routing-consolidada.md`](routing-consolidada.md)

**Temas cubiertos**:
- ¿Qué es Routing en Leaf?
- Basic Routing
- Route Groups
- Dynamic Routing
- Middleware
- Resource Routes

**Decisiones confirmadas**:
- Uso del sistema de routing de Leaf para el proyecto

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/Legado/routing-leaf.md
- doc_proyecto/concepto-de-proyecto.md

---

### 6. UI Templates
**Archivo**: [`ui-templates-consolidada.md`](ui-templates-consolidada.md)

**Temas cubiertos**:
- Soporte frontend en Leaf PHP
- Gentelella (Bootstrap 5 Admin Dashboard)
- Alternativas evaluadas (TailAdmin con React/Next.js)
- Matriz de comparación

**Decisiones confirmadas**:
- Gentelella como UI para MVP

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/Legado/evaluacion-ui-templates-leaf.md
- doc_proyecto/concepto-de-proyecto.md

---

### 7. Configuración
**Archivo**: [`configuracion-consolidada.md`](configuracion-consolidada.md)

**Temas cubiertos**:
- ¿Qué es Config en Leaf?
- Configuración básica
- Configuración de entorno
- Variables de entorno disponibles
- Configuración para despliegue

**Decisiones confirmadas**:
- Uso del sistema de configuración de Leaf para el proyecto

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/config-deployment-error-handling-di-docker-testing-guard-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/pasos-siguientes-despues-instalacion.md

---

### 8. Request, Response, Headers, CORS y Guard
**Archivo**: [`request-response-headers-cors-consolidada.md`](request-response-headers-cors-consolidada.md)

**Temas cubiertos**:
- Request en Leaf
- Response en Leaf
- Headers en Leaf
- CORS (Cross-Origin Resource Sharing)
- Guard para seguridad

**Decisiones confirmadas**:
- Uso de los componentes Request, Response, Headers y CORS de Leaf

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/request-response-headers-cors-guard-leaf.md
- doc_proyecto/concepto-de-proyecto.md

---

### 9. Arquitectura MVC
**Archivo**: [`arquitectura-mvc-consolidada.md`](arquitectura-mvc-consolidada.md)

**Temas cubiertos**:
- Conceptualización por MVC (Models, Views, Controllers)
- Estructura de carpetas MVC
- Cómo integrar MVC en el proyecto
- Flujo de datos en MVC

**Decisiones confirmadas**:
- Arquitectura MVC para el proyecto

**Documentos fuente**:
- doc_proyecto/concepto-de-proyecto.md
- doc_respuestas-leaf-docs-researcher/Legado/mvc-modules-scaffolding-explicacion.md
- doc_respuestas-leaf-docs-researcher/Legado/integrar-mvc-y-decision-modulos.md

---

### 10. Sistema Multi-Agente
**Archivo**: [`sistema-multiagente-consolidada.md`](sistema-multiagente-consolidada.md)

**Temas cubiertos**:
- Qué tipo de sistema parece estar definiéndose
- Señales documentales que sugieren la necesidad de agentes
- Procesos identificados en la documentación
- Responsabilidades separables identificadas
- Posibles agentes candidatos

**Decisiones confirmadas**:
- Sistema multi-agente para automatización de tareas de análisis documental

**Documentos fuente**:
- doc_respuestas-leaf-docs-researcher/rc_analisis-estrategico-agentes-multiagente.md

---

### 11. Evaluación Feature Toggle (Funcionalidades de Aplicación)
**Archivo**: [`evaluacion-feature-toggle-funcionalidades-app-leaf.md`](evaluacion-feature-toggle-funcionalidades-app-leaf.md)

**Temas cubiertos**:
- Distinción entre funcionalidades de aplicación vs módulos de Leaf
- Compatibilidad del sistema de feature toggle con la filosofía de Leaf
- Mecanismos de Leaf para implementar feature toggle
- Middleware para verificar módulos activos
- Rutas condicionales por módulo

**Decisiones confirmadas**:
- Sistema de Feature Toggle para funcionalidades de aplicación

**Documentos fuente**:
- Documentación oficial de Leaf PHP
- Análisis del proyecto actual

---

## Problemas Detectados

### Duplicaciones Identificadas

| Tema | Documentos con duplicación | Severidad |
|--------|---------------------------|------------|
| Autenticación (Sesiones vs JWT) | 4 documentos en Legado | Alta |
| Módulos Leaf | 3 documentos en Legado | Media |
| UI Templates | 2 documentos | Media |
| Routing | 2 documentos | Baja |

### Contradicciones Identificadas

| Tema | Contradicción | Resolución |
|--------|---------------|-------------|
| Elección Sesiones vs JWT | Documentos de evaluación recomiendan diferentes enfoques | Decisión confirmada en concepto-de-proyecto.md: Sesiones + RBAC para MVP |

---

## Decisiones Pendientes

| Decisión | Estado | Impacto |
|----------|--------|---------|
| Estrategia de caché para feature toggles | ⏳ Pendiente | Rendimiento |
| Nivel de granularidad de feature toggles (módulos vs submódulos) | ⏳ Pendiente | Flexibilidad |
| Cuándo incluir CRUD de gestión de feature toggles | ⏳ Pendiente | Administración |
| Estrategia de invalidación de sesiones | ⏳ Pendiente | Seguridad |
| Cuándo migrar a JWT (si es necesario) | ⏳ Pendiente | Arquitectura futura |
| Almacenamiento de sesiones (BD vs Redis) | ⏳ Pendiente | Escalabilidad |

---

## Recomendaciones para Mejoras Futuras

### Para Documentación

1. **Actualizar documentos fuente** con decisiones tomadas
2. **Eliminar documentos obsoletos** en la carpeta Legado
3. **Mantener vistas consolidadas** actualizadas cuando se agregue nueva documentación
4. **Crear vistas consolidadas** para temas adicionales según necesidad

### Para Proceso de Desarrollo

1. **Usar vistas consolidadas** como referencia principal durante desarrollo
2. **Actualizar vistas consolidadas** cuando se tomen nuevas decisiones
3. **Mantener trazabilidad** a documentos fuente para auditoría
4. **Revisar vistas consolidadas** periódicamente para detectar inconsistencias

---

## Metadatos

**Total de documentos analizados**: 17
**Total de vistas consolidadas generadas**: 11
**Total de duplicaciones detectadas**: 4
**Total de contradicciones detectadas**: 1
**Total de decisiones pendientes**: 6

---

## Referencias

**Agente**: agt-doc-consolidator
**Fecha de consolidación**: 2026-03-25
**Versión**: 1.0
