# Autenticación - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**:
- doc_respuestas-leaf-docs-researcher/Legado/sistema-autenticacion-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md
- doc_respuestas-leaf-docs-researcher/Legado/validacion-enfoque-jwt-rbac-leaf.md
- doc_respuestas-leaf-docs-researcher/Legado/validacion-enfoque-sesiones-rbac-leaf.md
- doc_proyecto/concepto-de-proyecto.md
- SETUP_COMPLETE.md
- PROJECT_README.md

---

## Resumen Ejecutivo

El proyecto requiere un sistema de autenticación para una web-app con Gentelella (Bootstrap 5 Admin Dashboard), desplegada en Railway. Se han evaluado dos enfoques principales: **Sesiones** y **JWT (JSON Web Tokens)**, ambos con soporte para RBAC (Role-Based Access Control).

**Decisiones Confirmadas**:
- ✅ Sesiones + RBAC para MVP (según documentación del proyecto)
- ✅ Session + JWT ready (según SETUP_COMPLETE.md)

---

## Información Confirmada

### Características del Proyecto

| Aspecto | Confirmado |
|---------|-----------|
| **Framework** | Leaf PHP |
| **UI** | Gentelella (Bootstrap 5 Admin Dashboard) |
| **Arquitectura** | Server-side rendering con Blade |
| **Despliegue** | Railway |
| **Fase** | MVP con dashboard de administración |

### Necesidades de Autenticación

- User Login
- User Sign Up
- Auth User (obtener usuario autenticado)
- Protección de rutas
- Roles y permisos (RBAC básico)

### Componentes Disponibles en el Proyecto

**Confirmado desde el código y SETUP_COMPLETE.md**:
- `AuthMiddleware.php` - Middleware para proteger rutas
- `User.php` - Modelo con métodos para CRUD, hash y verificación de contraseñas
- Migraciones para tablas:
  - `users`
  - `password_reset_tokens`
  - `sessions`
- `UserSeeder` - Seeder con 4 usuarios de prueba (1 admin + 3 test)

**Credenciales de Test** (fuente: SETUP_COMPLETE.md):

| Email | Password | Rol |
|-------|----------|-----|
| admin@example.com | password123 | Admin |
| john@example.com | password123 | User |
| jane@example.com | password123 | User |
| bob@example.com | password123 | User |

---

## Comparación: Sesiones vs JWT

### Matriz de Comparación

| Aspecto | Sesiones | JWT | Evaluación para Proyecto |
|---------|----------|-----|---------------------------|
| **Estado** | Stateful | Stateless | Sesiones más simples para web tradicional |
| **Almacenamiento** | Servidor (archivos, BD, Redis) | Cliente (localStorage, cookies) | Sesiones requieren almacenamiento compartido en Railway |
| **Escalabilidad** | Media (requiere almacenamiento compartido) | Alta (cualquier servidor puede validar) | Sesiones suficientes para MVP en Railway |
| **Invalidación** | Fácil (destroy session) | Difícil (blacklist o expiración) | Sesiones mucho más simples para invalidar |
| **Complejidad** | Baja | Media | Sesiones significativamente más simples |
| **Ideal para** | Web tradicional | APIs, SPAs | Proyecto es web tradicional con Gentelella |

### Matriz de Decisión

| Criterio | Peso | Sesiones | JWT | Ganador |
|-----------|--------|-----------|-----|----------|
| **Simplicidad de implementación** | Alto | 9/10 | 5/10 | Sesiones |
| **Velocidad de desarrollo** | Alto | 9/10 | 5/10 | Sesiones |
| **Escalabilidad en Railway** | Medio | 6/10 | 10/10 | JWT |
| **Adecuación para Gentelella** | Alto | 10/10 | 6/10 | Sesiones |
| **Flexibilidad futura** | Medio | 5/10 | 10/10 | JWT |
| **Seguridad** | Alto | 8/10 | 7/10 | Sesiones |
| **UX** | Medio | 9/10 | 7/10 | Sesiones |
| **Costo en Railway** | Bajo | 7/10 | 8/10 | Empate |
| **TOTAL** | | 63/70 | 58/70 | **Sesiones** |

---

## Información Conflicta

### Contradicción Identificada

**Tema**: Elección entre Sesiones vs JWT

| Fuente | Recomendación |
|--------|---------------|
| `validacion-enfoque-jwt-rbac-leaf.md` | JWT es adecuado si se prioriza escalabilidad y flexibilidad futura |
| `validacion-enfoque-sesiones-rbac-leaf.md` | Sesiones son más adecuadas si se prioriza simplicidad y velocidad de desarrollo |
| `comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md` | Sesiones ganan en la matriz de decisión (63/70 vs 58/70) |
| `concepto-de-proyecto.md` | Decisión confirmada: Sesiones + RBAC para MVP |
| `SETUP_COMPLETE.md` | Autenticación: Session + JWT ready |

**Resolución**: La documentación del proyecto (`concepto-de-proyecto.md`) confirma la decisión de usar **Sesiones + RBAC para MVP**. `SETUP_COMPLETE.md` indica que el sistema está preparado para JWT ("Session + JWT ready"), lo que sugiere una arquitectura híbrida que permite migración futura. La contradicción entre documentos de evaluación refleja diferentes perspectivas, pero la decisión final está documentada.

---

## Responsabilidades: Leaf vs Desarrollador

### Usando Sesiones

| Responsabilidad | Leaf | Desarrollador |
|-----------------|--------|---------------|
| Estructura de middleware | ✅ Sí | ❌ No |
| Modelo de usuario base | ✅ Sí | ❌ No |
| Tabla de sesiones | ✅ Sí | ❌ No |
| Gestión de sesiones (start, get, set, destroy) | ⚠️ Probable | ⚠️ Probable |
| Lógica de login | ❌ No | ✅ Sí |
| Lógica de logout | ❌ No | ✅ Sí |
| Obtención de usuario autenticado | ❌ No | ✅ Sí |
| Protección de rutas | ✅ Sí (estructura) | ✅ Sí (implementación) |

### Usando JWT (para referencia futura)

| Responsabilidad | Leaf | Desarrollador |
|-----------------|--------|---------------|
| Estructura de middleware | ✅ Sí | ❌ No |
| Modelo de usuario base | ✅ Sí | ❌ No |
| Protección de rutas | ✅ Sí (estructura) | ❌ No |
| Generación de JWT | ❌ No confirmado | ✅ Sí (probable) |
| Validación de JWT | ❌ No confirmado | ✅ Sí (probable) |
| Refresh tokens | ❌ No confirmado | ✅ Sí (probable) |
| Obtención de usuario autenticado | ❌ No confirmado | ✅ Sí (probable) |

---

## Decisiones Pendientes

| Decisión | Estado | Impacto |
|----------|--------|---------|
| Estrategia de invalidación de sesiones | ⏳ Pendiente | Seguridad |
| Almacenamiento de sesiones (BD vs Redis) | ⏳ Pendiente | Escalabilidad |
| Cuándo migrar a JWT (si es necesario) | ⏳ Pendiente | Arquitectura futura |

---

## Flujo Conceptual de Autenticación con Sesiones

### 1. User Sign Up (Registro)

```
Usuario → Leaf (Signup) → Validar datos → Hash password → Crear usuario en BD → Crear sesión → Redirigir a dashboard
```

**Responsabilidades**:
- **Leaf**: Estructura de rutas, modelo de usuario, gestión de sesiones (probable)
- **Desarrollador**: Validación de datos, hash password, creación de sesión

### 2. User Login

```
Usuario → Leaf (Login) → Validar credenciales → Verificar password → Crear sesión → Redirigir a dashboard
```

**Responsabilidades**:
- **Leaf**: Estructura de rutas, modelo de usuario (hash/verify), gestión de sesiones (probable)
- **Desarrollador**: Validación de credenciales, creación de sesión

### 3. Protección de Rutas

```
Solicitud → AuthMiddleware → Verificar sesión → Si no hay sesión → Redirigir a login o 401
```

**Responsabilidades**:
- **Leaf**: Estructura de middleware, aplicación a rutas, gestión de sesiones (probable)
- **Desarrollador**: Verificación de sesión, redirección o error

### 4. Auth User (Obtener Usuario Autenticado)

```
Solicitud → Leer sesión → Extraer user_id → Consultar usuario → Devolver usuario con roles/permisos
```

**Responsabilidades**:
- **Leaf**: Gestión de sesiones (probable)
- **Desarrollador**: Lectura de sesión, consulta de usuario, devolución de datos

---

## Recomendaciones

### Para MVP

✅ **Usar Sesiones + RBAC**
- Más simple de implementar
- Blade y Leaf tienen soporte nativo
- El scaffold ya tiene tabla de `sessions` creada
- Ideal para web tradicional con Gentelella

### Para Railway

✅ **Considerar almacenamiento compartido**
- Si escalas horizontalmente, necesitas Redis o BD para sesiones
- Para MVP, almacenamiento en archivos es suficiente

### Para Futuro

⏳ **Evaluar migración a JWT si:**
- Agregas API separada
- Agregas aplicación móvil
- Necesitas escalar significativamente

---

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| Sesiones + RBAC para MVP | concepto-de-proyecto.md | ✅ Confirmado |
| Session + JWT ready | SETUP_COMPLETE.md | ✅ Confirmado |
| Gentelella como UI | concepto-de-proyecto.md, evaluacion-ui-templates-leaf.md | ✅ Confirmado |
| AuthMiddleware disponible | sistema-autenticacion-leaf.md | ✅ Confirmado |
| Tabla sessions creada | sistema-autenticacion-leaf.md, validacion-enfoque-sesiones-rbac-leaf.md | ✅ Confirmado |
| UserSeeder con 4 usuarios de prueba | SETUP_COMPLETE.md | ✅ Confirmado |
| JWT requiere librería externa | validacion-enfoque-jwt-rbac-leaf.md | ✅ Confirmado |
| Sesiones ganan en matriz de decisión | comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md | ✅ Confirmado |
| Invalidación de sesiones fácil | validacion-enfoque-sesiones-rbac-leaf.md, comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md | ✅ Confirmado |
| JWT escala mejor en Railway | validacion-enfoque-jwt-rbac-leaf.md, comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md | ✅ Confirmado |

---

## Referencias a Documentos Fuente

1. **sistema-autenticacion-leaf.md** - Descripción general de autenticación en Leaf
2. **comparacion-sesiones-vs-jwt-leaf-railway-gentelella.md** - Comparación detallada entre sesiones y JWT
3. **validacion-enfoque-jwt-rbac-leaf.md** - Validación del enfoque JWT + RBAC
4. **validacion-enfoque-sesiones-rbac-leaf.md** - Validación del enfoque Sesiones + RBAC
5. **concepto-de-proyecto.md** - Concepto general del proyecto con decisión confirmada
6. **SETUP_COMPLETE.md** - Documentación del setup completado con autenticación implementada
7. **PROJECT_README.md** - README principal del proyecto con información de autenticación
