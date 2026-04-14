# Módulos Leaf - Decisiones

**Fecha de creación**: 2026-03-25
**Última actualización**: 2026-03-25
**Tema**: Módulos y Dependencias de Leaf

---

## Índice de Contenido

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Tabla Resumen](#tabla-resumen)
3. [Decisiones Confirmadas](#decisiones-confirmadas)
4. [Decisiones Pendientes](#decisiones-pendientes)
5. [Decisiones Descartadas](#decisiones-descartadas)
6. [Mapa de Dependencias](#mapa-de-dependencias)
7. [Próximos Pasos](#próximos-pasos)

---

## Resumen Ejecutivo

El proyecto usará Leaf PHP como framework principal con módulos específicos para el MVP.

**Decisión principal**: Módulos mínimos necesarios para MVP.

---

## Tabla Resumen

| ID | Decisión | Estado | Impacto | Fuente | Dependencias |
|----|----------|--------|---------|--------|--------------|
| MOD-001 | Módulos Leaf para MVP | CONFIRMADA | ALTO | `doc_proyecto/concepto-de-proyecto.md:5` | - |
| MOD-002 | Sistema de colas (fase posterior) | PENDIENTE | MEDIO | `doc_proyecto/concepto-de-proyecto.md:5` | - |

---

## Decisiones Confirmadas

### MOD-001: Módulos Leaf para MVP

**Descripción**: Usar los siguientes módulos de Leaf para el MVP:

**Módulos confirmados**:
- `leafs/leaf` - Framework core
- `leafs/http` - HTTP layer
- `leafs/anchor` - Seguridad básica
- `leafs/exception` - Manejo de excepciones
- `leafs/db` - Capa de base de datos (PostgreSQL)
- `leafs/mvc` - Wrapper MVC
- Blade - Motor de vistas (recomendado para Leaf)

**Justificación**:
- Mínimos necesarios para funcionalidad del MVP
- Compatibilidad garantizada con Leaf
- Documentación disponible

**Impacto**:
- Dependencias del proyecto
- Estructura de código

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 5`

**Fecha de decisión**: 2026-03-24

---

## Decisiones Pendientes

### MOD-002: Sistema de Colas

**Descripción**: Evaluar inclusión de sistema de colas para procesamiento asíncrono.

**Alternativas en Consideración**:
- **Redis + worker**: Implementar sistema de colas propio
- **Librería de PHP**: enqueue, rabbitmq-bundle
- **Sin colas**: Procesamiento síncrono para MVP

**Bloqueantes**: Ninguno

**Dependencias**:
- Workflow de OpenAI API (puede requerir procesamiento asíncrono)

**Impacto**:
- Arquitectura de procesamiento de workflows
- Infraestructura (Redis si se usa)

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 5`

**Recomendación**:
- **MVP**: Sin colas (procesamiento síncrono si el tiempo lo permite)
- **Futuro**: Evaluar Redis + worker si workflows son lentos

**Estado actual**: Pendiente - no bloquea MVP

---

## Decisiones Descartadas

*Ninguna decisión descartada documentada aún.*

---

## Mapa de Dependencias

```
MOD-001 (Módulos MVP)
    └── MOD-002 (Colas) - PENDIENTE (no bloqueante)
```

**Decisiones Bloqueantes**: Ninguna

---

## Próximos Pasos

1. **Instalar módulos confirmados**
2. **Evaluar necesidad de colas después de implementar workflow**
3. **Actualizar inventario con módulos instalados**

---

**Referencias**:
- `doc_proyecto/concepto-de-proyecto.md:5`
- `doc_consolidada/modulos-leaf-consolidada.md`
- `ANALYSIS_AND_ADOPTION_PLAN.md`
