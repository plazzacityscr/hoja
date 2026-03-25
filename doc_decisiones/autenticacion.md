# Autenticación - Decisiones

**Fecha de creación**: 2026-03-25
**Última actualización**: 2026-03-25
**Tema**: Autenticación y RBAC

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

El proyecto requiere un sistema de autenticación para una web-app con Gentelella (Bootstrap 5 Admin Dashboard), desplegada en Railway. Se evaluaron dos enfoques: **Sesiones** y **JWT**.

**Decisión principal**: Sesiones + RBAC para MVP con almacenamiento en Redis.

---

## Tabla Resumen

| ID | Decisión | Estado | Impacto | Fuente | Dependencias |
|----|----------|--------|---------|--------|--------------|
| | AUTH-001 | Sistema de autenticación para MVP | CONFIRMADA | ALTO | `doc_proyecto/concepto-de-proyecto.md:4.1` | - |
| | AUTH-002 | Almacenamiento de sesiones en Redis | CONFIRMADA | ALTO | `doc_proyecto/concepto-de-proyecto.md:4.1` | AUTH-001 |
| | AUTH-003 | Integración con RBAC | CONFIRMADA | MEDIO | `doc_proyecto/concepto-de-proyecto.md:4.1` | AUTH-001 |

---

## Decisiones Confirmadas

### AUTH-001: Sistema de Autenticación para MVP

**Descripción**: Usar SESIONES + RBAC para autenticación en MVP.

**Alternativas Consideradas**:
- **JWT**: Rechazado por mayor complejidad innecesaria para MVP
- **OAuth**: No considerado para MVP

**Justificación**: 
- Más simple y rápido de implementar
- Compatible con arquitectura server-side rendering de Gentelella
- Leaf PHP tiene soporte nativo para sesiones
- El scaffold ya tiene tabla de `sessions` creada

**Impacto**: 
- Arquitectura de autenticación
- Middleware de protección de rutas
- Despliegue en Railway

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 4.1`

**Fecha de decisión**: 2026-03-24

---

### AUTH-003: Integración con RBAC

**Descripción**: El sistema de autenticación debe integrar RBAC (Role-Based Access Control) para gestión de permisos.

**Alternativas Consideradas**:
- **Sin RBAC**: Rechazado - se necesita control de acceso para MVP
- **ABAC (Attribute-Based)**: Rechazado - complejidad innecesaria

**Justificación**:
- MVP requiere diferenciación de roles (admin, usuario)
- RBAC es simple de implementar sobre sesiones
- Compatible con Gentelella

**Impacto**:
- Estructura de datos (tablas de roles y permisos)
- Middleware de verificación de roles
- UI condicional por permisos

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 4.1`

**Fecha de decisión**: 2026-03-24

---

### AUTH-002: Almacenamiento de Sesiones en Redis

**Descripción**: Usar Redis para almacenamiento de sesiones.

**Alternativas Consideradas**:
- **Archivos**: Rechazado - No escala horizontalmente en Railway
- **Base de datos (PostgreSQL)**: Rechazado - Consultas adicionales en cada request
- **Redis**: Seleccionado - Mejor rendimiento y escalabilidad horizontal

**Justificación**:
- Redis es extremadamente rápido para operaciones de lectura/escritura de sesiones
- Soporta escalabilidad horizontal en Railway (todas las instancias comparten el mismo Redis)
- Railway ofrece Redis como servicio addon
- TTL automático de sesiones (expiración nativa)
- Menor carga en base de datos principal

**Impacto**:
- Configuración de Railway (agregar servicio Redis)
- Variables de entorno adicionales (REDIS_URL)
- Configuración de session handler en PHP
- Escalabilidad horizontal habilitada

**Configuración requerida**:
- Servicio Redis en Railway
- Variable de entorno `REDIS_URL`
- Configuración de session handler en `config/app.php`

**Fuente**: `doc_proyecto/concepto-de-proyecto.md:sección 4.1`

**Fecha de decisión**: 2026-03-25

---

## Decisiones Descartadas

### AUTH-D001: JWT para MVP

**Descripción**: Usar JWT (JSON Web Tokens) como sistema de autenticación principal.

**Razón del Descarte**:
- Mayor complejidad de implementación
- Requiere librería externa (no nativa en Leaf)
- Invalidación más compleja (blacklist o expiración)
- Menos adecuado para server-side rendering con Gentelella

**Fuente**: `doc_respuestas-leaf-docs-researcher/Legado/comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md`

**Fecha de descarte**: 2026-03-24

---

## Mapa de Dependencias

```
AUTH-001 (Sistema autenticación)
    ├── AUTH-002 (Almacenamiento sesiones en Redis) - CONFIRMADA
    └── AUTH-003 (RBAC) - CONFIRMADA
```

**Decisiones Bloqueantes**: Ninguna

---

## Próximos Pasos

1. **Configurar servicio Redis en Railway**
   - Agregar servicio Redis al proyecto
   - Obtener `REDIS_URL` de Railway
2. **Configurar session handler en PHP**
   - Actualizar `config/app.php` para usar Redis
   - Configurar TTL de sesiones
3. **Implementar AuthMiddleware con Redis**
4. **Probar escalabilidad horizontal**

---

**Referencias**:
- `doc_proyecto/concepto-de-proyecto.md:4.1`
- `doc_consolidada/autenticacion-consolidada.md`
- `doc_respuestas-leaf-docs-researcher/Legado/comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md`
