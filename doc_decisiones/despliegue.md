# Despliegue - Decisiones

**Fecha de creación**: 2026-03-25
**Última actualización**: 2026-03-25
**Tema**: Despliegue e Infraestructura

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

El proyecto se desplegará en **Railway**, una plataforma PaaS que soporta PHP de forma nativa.

**Decisión principal**: Railway como plataforma de despliegue.

---

## Tabla Resumen

| ID | Decisión | Estado | Impacto | Fuente | Dependencias |
|----|----------|--------|---------|--------|--------------|
| DEPLOY-001 | Railway como plataforma de despliegue | CONFIRMADA | ALTO | `doc_proyecto/concepto-de-proyecto.md` | - |
| DEPLOY-002 | Método de despliegue (automático desde GitHub) | CONFIRMADA | ALTO | `railway.json`, `SETUP_COMPLETE.md` | DEPLOY-001 |

---

## Decisiones Confirmadas

### DEPLOY-001: Railway como Plataforma de Despliegue

**Descripción**: Usar Railway como plataforma de despliegue para el proyecto.

**Alternativas Consideradas**:
- **Docker + VPS**: Rechazado - mayor complejidad de gestión
- **Shared Hosting**: Rechazado - menos flexibilidad
- **Cloudflare Workers**: No aplicable para PHP con Leaf

**Justificación**:
- Soporte nativo para PHP
- Integración con PostgreSQL
- Variables de entorno fáciles de configurar
- Despliegue automático desde Git
- SSL gratuito
- Escalado horizontal automático

**Impacto**:
- Infraestructura del proyecto
- Configuración de variables de entorno
- Estrategia de sesiones (requiere almacenamiento compartido si escala)

**Fuente**: `doc_proyecto/concepto-de-proyecto.md`

**Fecha de decisión**: 2026-03-24

---

## Decisiones Confirmadas

### DEPLOY-002: Método de Despliegue en Railway

**Descripción**: Despliegue automático desde GitHub a Railway.

**Alternativas Consideradas**:
- **Despliegue manual desde terminal**: Rechazado - menos eficiente para desarrollo iterativo
- **CI/CD con GitHub Actions**: No necesario - Railway ya proporciona despliegue automático desde Git

**Justificación**:
- Railway detecta automáticamente cambios en el repositorio de GitHub
- Construcción automática usando `Dockerfile`
- Despliegue automático con SSL gratuito
- Sin necesidad de configurar pipelines de CI/CD adicionales
- Logs de despliegue disponibles en el dashboard de Railway

**Impacto**:
- Flujo de trabajo simplificado para el equipo
- Despliegues automáticos en cada push a la rama principal
- Rollback fácil desde el dashboard de Railway

**Configuración implementada**:
- ✅ `railway.json` - Configuración de Railway con Dockerfile
- ✅ `Dockerfile` - Imagen de producción optimizada
- ✅ `Procfile` - Fallback para Railway/Heroku

**Fuente**: `railway.json`, `SETUP_COMPLETE.md`, `doc_consolidada/despliegue-consolidada.md`

**Fecha de decisión**: 2026-03-25

---

## Decisiones Pendientes

*No hay decisiones pendientes sobre despliegue. El método de despliegue está confirmado y configurado.*

---

## Decisiones Descartadas

*Ninguna decisión descartada documentada aún.*

---

## Mapa de Dependencias

```
DEPLOY-001 (Railway como plataforma de despliegue)
    └── DEPLOY-002 (Despliegue automático desde GitHub) - CONFIRMADA
```

**Decisiones Bloqueantes**: Ninguna

---

## Próximos Pasos

### Configuración Inicial (Primera vez)

1. **Crear proyecto en Railway**
   - Ir a https://railway.com
   - Click "New Project" → "Deploy from GitHub"
   - Seleccionar el repositorio del proyecto
   - Railway detecta automáticamente `railway.json` y `Dockerfile`

2. **Configurar base de datos en Railway**
   - Click "New" → "Database" → PostgreSQL
   - Railway inyecta `DATABASE_URL` automáticamente como variable de entorno

3. **Configurar variables de entorno en Railway**
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_KEY` (generar con `openssl rand -hex 32`)
   - Otras variables según `.env.example`

### Despliegues Subsecuentes

- Push de código a GitHub
- Railway detecta cambios automáticamente
- Despliegue automático con logs disponibles en dashboard
- Rollback disponible desde el dashboard de Railway

### Tareas de Mantenimiento

- Monitorear logs de despliegue en Railway
- Configurar dominio personalizado si es necesario
- Configurar alertas de errores
- Revisar métricas de uso y costo

---

**Referencias**:
- `doc_proyecto/concepto-de-proyecto.md`
- `doc_consolidada/despliegue-consolidada.md`
- `.governance/reglas_proyecto.md:R8.b`
