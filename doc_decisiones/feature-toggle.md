# Feature Toggle - Decisiones

**Fecha de creación**: 2026-03-25
**Última actualización**: 2026-03-25
**Tema**: Sistema de Feature Toggle

---

## Índice de Contenido

1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Tabla Resumen](#tabla-resumen)
3. [Decisiones Confirmadas](#decisiones-confirmadas)
4. [Decisiones Descartadas](#decisiones-descartadas)
5. [Mapa de Dependencias](#mapa-de-dependencias)
6. [Próximos Pasos](#próximos-pasos)

---

## Resumen Ejecutivo

El proyecto incorporará un sistema de activación de funcionalidades (feature toggle) que permite activar o desactivar módulos y submódulos de la aplicación.

**Decisión principal**: Sistema de Feature Toggle para funcionalidades de aplicación (NO módulos de Leaf).

---

## Tabla Resumen

| ID | Decisión | Estado | Impacto | Fuente | Dependencias |
|----|----------|--------|---------|--------|--------------|
| | FT-001 | Sistema de Feature Toggle para funcionalidades | CONFIRMADA | ALTO | `doc_proyecto/concepto-de-proyecto.md:5.1` | - |
| | FT-002 | Estrategia de caché para feature toggles | CONFIRMADA | MEDIO | `doc_proyecto/concepto-de-proyecto.md:5.1` | FT-001, AUTH-002 |
| | FT-003 | Nivel de granularidad (módulos y submódulos) | CONFIRMADA | ALTO | `doc_proyecto/concepto-de-proyecto.md:5.1` | FT-001 |

---

## Decisiones Confirmadas

### FT-001: Sistema de Feature Toggle para Funcionalidades

**Descripción**: El proyecto usará un sistema de feature toggle para controlar EXCLUSIVAMENTE las funcionalidades de la aplicación, no los módulos de Leaf.

**Alternativas Consideradas**:
- **Sin feature toggle**: Rechazado - se necesita flexibilidad para MVP
- **Feature toggle para módulos de Leaf**: Rechazado - los módulos de Leaf siempre están disponibles

**Justificación**:
- Permite activar/desactivar funcionalidades de negocio dinámicamente
- Compatible con filosofía de Leaf (flexible, no opinativo)
- Configuración persistente en tablas de base de datos
- Control de acceso por roles integrado

**Impacto**:
- Estructura de datos (tablas de feature_toggles)
- Middleware de verificación de módulos
- Rutas condicionales
- UI condicional

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 5.1`

**Fecha de decisión**: 2026-03-24

---

### FT-002: Estrategia de Caché para Feature Toggles en Redis

**Descripción**: Usar Redis para cachear la configuración de feature toggles y evitar consultas repetidas a base de datos.

**Alternativas Consideradas**:
- **Sin caché**: Rechazado - Consultar BD en cada solicitud es costoso
- **Caché en memoria**: Rechazado - No escala horizontalmente
- **Caché compartido (Redis)**: Seleccionado - Mejor rendimiento y escalabilidad

**Justificación**:
- Redis es extremadamente rápido para operaciones de lectura/escritura
- Soporta escalabilidad horizontal en Railway (todas las instancias comparten el mismo Redis)
- Servicio Redis ya requerido para sesiones (AUTH-002)
- Invalidación de caché simple (delete key)
- TTL automático para expiración de caché

**Impacto**:
- Rendimiento de la aplicación
- Configuración de caché en Redis
- Invalidación de caché cuando se modifican toggles

**Configuración requerida**:
- Servicio Redis en Railway (compartido con sesiones)
- Variable de entorno `REDIS_URL`
- Implementación de caché en middleware de feature toggles

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 5.1`

**Fecha de decisión**: 2026-03-25

---

### FT-003: Nivel de Granularidad (Módulos y Submódulos)

**Descripción**: El sistema de feature toggle controlará tanto módulos completos como submódulos específicos.

**Alternativas Consideradas**:
- **Solo módulos**: Rechazado - Insuficiente flexibilidad
- **Módulos + submódulos**: Seleccionado - Máxima flexibilidad

**Justificación**:
- Permite control fino de funcionalidades (ej: "proyectos.crear", "proyectos.editar")
- Compatible con RBAC (roles pueden tener acceso a submódulos específicos)
- Estructura de datos flexible (parent_id para submódulos)
- UI más granular para administración de permisos
- Facilita implementación progresiva de funcionalidades

**Impacto**:
- Complejidad de estructura de datos (tabla con parent_id)
- Flexibilidad del sistema
- UI de administración más compleja
- Middleware de verificación de granularidad

**Estructura de datos propuesta**:
```sql
CREATE TABLE feature_toggles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    is_active BOOLEAN DEFAULT false,
    parent_id INTEGER REFERENCES feature_toggles(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 5.1`

**Fecha de decisión**: 2026-03-25

---

## Decisiones Descartadas

*Ninguna decisión descartada documentada aún.*

---

## Mapa de Dependencias

```
FT-001 (Feature Toggle System)
    ├── FT-002 (Caché en Redis) - CONFIRMADA
    └── FT-003 (Granularidad: módulos y submódulos) - CONFIRMADA
```

**Decisiones Bloqueantes**: Ninguna

---

## Próximos Pasos

1. **Diseñar estructura de datos para feature toggles**
   - Responsable: `agt-feature-toggle-designer`
   - Incluir parent_id para submódulos
2. **Crear migraciones para feature_toggles**
3. **Implementar ModuleActiveMiddleware**
4. **Implementar ModuleAccessMiddleware**
5. **Implementar caché en Redis para feature toggles**
   - Compartir servicio Redis con sesiones
   - Implementar invalidación de caché
6. **Probar escalabilidad horizontal con Redis**

---

**Referencias**:
- `doc_proyecto/concepto-de-proyecto.md:5.1`
- `doc_consolidada/feature-toggle-consolidada.md`
- `doc_proyecto/evaluacion-feature-toggle-funcionalidades-app-leaf.md`
