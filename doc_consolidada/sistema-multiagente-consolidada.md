# Sistema Multi-Agente - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**: 
- doc_respuestas-leaf-docs-researcher/rc_analisis-estrategico-agentes-multiagente.md

---

## Resumen Ejecutivo

La documentación revela un **sistema de gestión y análisis de inmuebles** basado en inteligencia artificial, estructurado en múltiples capas. Se han identificado señales documentales que sugieren la necesidad de agentes para automatizar tareas repetitivas de análisis documental.

**Decisión Confirmada**: Sistema multi-agente para automatización de tareas de análisis documental.

---

## Qué Tipo de Sistema Parece Estar Definiéndose

### Capa de Negocio

- Gestión de proyectos (proyecto = inmueble)
- Ejecución de workflows de análisis con OpenAI API (9 pasos)
- Almacenamiento de informes en sistema de archivos
- UI para edición y visualización (Gentelella + Blade)

### Capa Técnica

- Framework: Leaf PHP (microframework modular)
- Base de datos: PostgreSQL
- Autenticación: Sesiones + RBAC (decisión confirmada para MVP)
- Despliegue: Railway
- UI: Gentelella (Bootstrap 5 Admin Dashboard)

### Capa de Configuración

- Sistema de Feature Toggle para activar/desactivar funcionalidades
- Control de acceso por roles y permisos
- Modularidad funcional (módulos y submódulos)

---

## Señales Documentales que Sugeren la Necesidad de Agentes

### Señales Explícitas en Documentación

| Señal | Evidencia Documental | Implicación para Agentes |
|--------|---------------------|-------------------------|
| **Documentación extensa y fragmentada** | 12+ archivos en `doc_proyecto/` y `doc_respuestas-leaf-docs-researcher/` | Necesidad de consolidación y navegación documental |
| **Múltiples decisiones arquitectónicas pendientes** | Secciones "Supuestos, Dudas y Decisiones Pendientes" en múltiples docs | Necesidad de rastreo y validación de decisiones |
| **Flujo doc-first implícito** | Referencias cruzadas entre documentos, análisis de requisitos → evaluación → validación | Proceso estructurado que podría automatizarse |
| **Módulos y dependencias complejas** | Evaluación de módulos Leaf, feature toggle, RBAC | Necesidad de análisis de impacto y consistencia |
| **Fases de proyecto (MVP → posterior)** | Distinción clara entre MVP y funcionalidades futuras | Necesidad de priorización y roadmap |

### Señales Implícitas

- La documentación fue generada por un agente (`leaf-docs-researcher`)
- Existe un patrón de investigación → análisis → recomendación
- Hay información clasificada (hecho documentado, observación, inferencia, conocimiento general)

---

## Procesos Identificados en la Documentación

### Proceso de Investigación Documental

```
Búsqueda en repositorio → Extracción de hechos → Clasificación → Análisis → Recomendación
```

### Proceso de Toma de Decisiones

```
Identificación de necesidad → Evaluación de alternativas → Comparación → Recomendación → Decisión pendiente
```

### Proceso de Validación Arquitectónica

```
Propuesta → Evaluación de compatibilidad → Identificación de responsabilidades → Validación → Consideraciones
```

### Proceso de Diseño de Sistema

```
Requisitos → Módulos necesarios → Integración → Flujo conceptual → Implementación conceptual
```

---

## Responsabilidades Separables Identificadas

| Responsabilidad | Descripción | Frecuencia en Docs |
|-----------------|-------------|-------------------|
| **Búsqueda documental** | Localizar información en repositorio | Alta (base de todo análisis) |
| **Clasificación de información** | Distinguir hechos, observaciones, inferencias | Alta (presente en todos los docs) |
| **Evaluación de alternativas** | Comparar opciones (ej: sesiones vs JWT) | Media-Alta |
| **Validación de compatibilidad** | Verificar si algo encaja con filosofía Leaf | Media |
| **Análisis de responsabilidades** | Separar lo que hace Leaf vs desarrollador | Media-Alta |
| **Detección de dependencias** | Identificar módulos necesarios y sus relaciones | Media |
| **Generación de recomendaciones** | Producir conclusiones accionables | Alta |
| **Identificación de vacíos** | Señalar información faltante o incierta | Alta |

---

## Artefactos Documentales Recurrentes

| Artefacto | Propósito | Estructura Típica |
|-----------|-----------|-------------------|
| **Tabla de clasificación** | Distinguir tipos de información | Tipo \| Contenido \| Verificabilidad |
| **Matriz de comparación** | Evaluar alternativas | Aspecto \| Opción A \| Opción B \| Ganador |
| **Lista de responsabilidades** | Separar Leaf vs desarrollador | Responsabilidad \| Leaf \| Desarrollador |
| **Sección de dudas pendientes** | Registrar incertidumbre | Pregunta \| Decisión \| Impacto |
| **Flujo conceptual** | Describir procesos | Paso 1 → Paso 2 → Paso 3 |
| **Recomendaciones por fase** | Priorizar por MVP/posterior | Fase \| Acción \| Justificación |

---

## Decisiones Documentadas vs Pendientes

### Decisiones Confirmadas

- ✅ Gentelella como UI para MVP
- ✅ Sesiones + RBAC para autenticación (MVP)
- ✅ PostgreSQL como base de datos
- ✅ `leafs/db` como capa de datos
- ✅ Railway como plataforma de despliegue
- ✅ Sistema de Feature Toggle para funcionalidades

### Decisiones Pendientes (señaladas explícitamente)

- ⏳ Estrategia de caché para feature toggles
- ⏳ Nivel de granularidad de feature toggles (módulos vs submódulos)
- ⏳ Cuándo incluir CRUD de gestión de feature toggles
- ⏳ Estrategia de invalidación de sesiones
- ⏳ Cuándo migrar a JWT (si es necesario)
- ⏳ Almacenamiento de sesiones (BD vs Redis)

---

## Posibles Agentes Candidatos

### Criterio de Evaluación

Cada agente candidato se evalúa según:
- **Necesidad recurrente documentada**: ¿Aparece como tarea repetitiva en la documentación?
- **Responsabilidad separable**: ¿Es una función claramente delimitada?
- **Fase del flujo doc-first**: ¿Corresponde a una etapa identificable del proceso?
- **Dependencia clara**: ¿Tiene inputs/outputs definidos?
- **Carga cognitiva**: ¿Justifica la complejidad tener un agente especializado?

### Agente: doc-researcher

**Propósito Estratégico**: 
Investigar y extraer información específica de la documentación del proyecto de forma dirigida por consultas.

**Problema que Resolvería**:
- Búsqueda manual extensa en múltiples archivos documentales
- Riesgo de pasar por alto información relevante fragmentada
- Tiempo consumido en localizar hechos verificables vs inferencias

**Evidencia Documental que Justifica su Existencia**:
- Todos los documentos en `doc_respuestas-leaf-docs-researcher/` fueron generados por un agente con este rol
- Patrón consistente: consulta específica → búsqueda en repositorio → extracción → clasificación → respuesta
- La documentación misma menciona: "**Agente**: leaf-docs-researcher" en cada archivo

**Nivel de Prioridad**: **ALTA**

### Agente: doc-consolidator

**Propósito Estratégico**: 
Consolidar información fragmentada de múltiples documentos en vistas unificadas y coherentes.

**Problema que Resolvería**:
- Información duplicada o contradictoria entre documentos
- Dificultad para obtener visión holística de un tema disperso
- Referencias cruzadas manuales entre archivos

**Evidencia Documental que Justifica su Existencia**:
- Múltiples documentos tratan sobre el mismo tema (ej: autenticación)
- Información duplicada entre documentos
- Referencias cruzadas entre archivos
- Este documento es un ejemplo de consolidación

**Nivel de Prioridad**: **ALTA**

---

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Sistema de gestión de inmuebles con OpenAI API | rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |
| Documentación fragmentada en 12+ archivos | rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |
| Decisiones arquitectónicas pendientes | rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |
| Flujo doc-first implícito | rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |
| Documentación generada por leaf-docs-researcher | rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |
| Decisiones confirmadas (Gentelella, Sesiones+RBAC, etc.) | rc_analisis-estrategico-agentes-multiagente.md | ✅ Confirmado |

---

## Recomendaciones

### Para Implementación

✅ **Implementar agentes para tareas repetitivas**
- `doc-researcher` para búsqueda documental
- `doc-consolidator` para consolidación de información

### Para Orquestación

✅ **Definir flujo de trabajo entre agentes**
- Investigación → Análisis → Consolidación
- Validación de decisiones
- Identificación de vacíos

### Para Futuro

⏳ **Evaluar agentes adicionales**
- Agente para validación arquitectónica
- Agente para análisis de dependencias
- Agente para priorización de funcionalidades

---

## Referencias a Documentos Fuente

1. **rc_analisis-estrategico-agentes-multiagente.md** - Análisis Estratégico: Sistema Multi-Agente para Proyecto Leaf PHP
