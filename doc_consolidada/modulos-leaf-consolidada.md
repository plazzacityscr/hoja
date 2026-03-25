# Módulos Leaf - Vista Consolidada

**Fecha**: 2026-03-25
**Fuentes**:
- doc_respuestas-leaf-docs-researcher/Legado/mvc-modules-scaffolding-explicacion.md
- doc_respuestas-leaf-docs-researcher/Legado/integrar-mvc-y-decision-modulos.md
- doc_respuestas-leaf-docs-researcher/Legado/analisis-requisitos-proyecto-inmobiliario.md
- doc_proyecto/concepto-de-proyecto.md
- SETUP_COMPLETE.md
- PROJECT_README.md

---

## Resumen Ejecutivo

Leaf PHP es un microframework modular que permite instalar solo los componentes necesarios. Los módulos se instalan vía Composer y proporcionan capacidades específicas al framework.

**Decisión Confirmada**: Uso de módulos específicos según necesidades del proyecto.

---

## Qué son los Módulos de Leaf

### Concepto General

Los módulos de Leaf son paquetes opcionales que agregan funcionalidad específica al framework. Solo instalas lo que necesitas.

### Módulos Principales Identificados

**Confirmado desde SETUP_COMPLETE.md y PROJECT_README.md**:

| Módulo | Propósito | Confirmado en Proyecto |
|---------|-----------|------------------------|
| `leafs/leaf` | Framework core | ✅ Sí |
| `leafs/http` | HTTP layer | ✅ Sí |
| `leafs/db` | Database layer | ✅ Sí |
| `leafs/anchor` | Security utilities | ✅ Sí |
| `leafs/exception` | Exception handling | ✅ Sí |
| `leafs/alchemy` | Dev tools | ✅ Sí |
| `leafs/mvc` | Wrapper MVC para Leaf | ⚠️ Probable |
| `leafs/cors` | Soporte para CORS | ⚠️ Probable |

**Lista completa de dependencias PHP** (fuente: SETUP_COMPLETE.md):

```json
{
  "leafs/leaf": "Framework core",
  "leafs/http": "HTTP layer",
  "leafs/anchor": "Security utilities",
  "leafs/exception": "Exception handling",
  "leafs/db": "Database layer",
  "leafs/alchemy": "Dev tools"
}
```

---

## Cómo se Integran los Módulos

### Instalación

Los módulos se instalan vía Composer:

```bash
# Ejemplos de instalación
composer require leafs/leaf
composer require leafs/http
composer require leafs/db
composer require leafs/anchor
composer require leafs/exception
composer require leafs/mvc
composer require leafs/cors
```

### Uso en Código

Una vez instalados, los módulos se usan en el código según se necesiten:

```php
// Usar leafs/db
use Leaf\Db;

$users = Db::select('SELECT * FROM users');

// Usar leafs/http
app()->get('/', function () {
    return response()->json(['message' => 'Hello World']);
});

// Usar leafs/anchor
use Leaf\Anchor;

$email = Anchor::email($email)->validate();
```

---

## Módulos Recomendados para el Proyecto

### Mínimo (API-only, sin BD)

```
leafs/leaf          # Framework core
leafs/http          # HTTP layer
leafs/anchor        # Seguridad básica
leafs/exception     # Manejo de excepciones
```

### Básico (API + BD)

```
Mínimo +
leafs/db            # Capa de base de datos
```

### Completo (MVC + BD + Auth)

```
Básico +
leafs/mvc           # Wrapper MVC
Motor de vistas     # Blade/BareUI
Módulo de autenticación
Módulo de caché (si es necesario)
```

### Confirmado para el Proyecto

**Confirmado desde SETUP_COMPLETE.md y PROJECT_README.md**:

```
leafs/leaf          # Framework core
leafs/http          # HTTP layer
leafs/db            # Database layer (PostgreSQL en Railway, MySQL en Docker)
leafs/anchor        # Security utilities
leafs/exception     # Exception handling
leafs/alchemy       # Dev tools
leafs/mvc           # Wrapper MVC (probable)
Motor de vistas     # Blade (para Gentelella)
Autenticación       # Session + JWT ready
```

**Notas**:
- El proyecto usa `leafs/db` como capa de base de datos oficial
- Autenticación está configurada como "Session + JWT ready" (SETUP_COMPLETE.md)
- Base de datos: PostgreSQL en Railway, MySQL en Docker para desarrollo

---

## MVC y App Scaffolding

### MVC (Model-View-Controller)

**Qué es**: Una estructura de organización de código que separa tu aplicación en tres partes:
- **Models**: Manejan datos y lógica de negocio (en `app/Models/`)
- **Views**: Plantillas visuales (en `views/`)
- **Controllers**: Conectan modelos y vistas, manejan la lógica (en `app/Controllers/`)

**En Leaf**: Leaf core es un microframework simple. Para MVC completo, usa **leafmvc** que es "un wrapper MVC para leaf" según la documentación oficial.

### App Scaffolding

**Qué es**: Una estructura de proyecto pre-configurada lista para usar, con carpetas organizadas y archivos base.

**Estructura típica**:
```
leaf/
├── app/
│   ├── Controllers/     # Controladores
│   ├── Middleware/      # Middleware
│   └── Models/          # Modelos
├── config/              # Configuraciones
├── public/              # Raíz web
│   └── index.php        # Punto de entrada
├── routes/              # Definición de rutas
├── views/               # Plantillas
└── storage/             # Archivos de almacenamiento
```

**Cómo se crea**: Usando Leaf CLI:
```bash
leaf create <project-name> --basic
```

---

## Cómo Integrar MVC en el Proyecto

### Opción 1: Usar leafmvc (Recomendado para MVC completo)

**Qué hacer**: Instalar el wrapper MVC oficial de Leaf

**Cómo**:
```bash
composer require leafs/mvc
```

**Ventaja**: Proporciona estructura MVC completa pre-configurada.

### Opción 2: Crear estructura MVC manualmente

**Qué hacer**: Crear carpetas y archivos según la estructura del scaffold actual

**Pasos**:

1. **Crear estructura de carpetas**:
   ```
   app/
   ├── Controllers/     # Controladores
   ├── Middleware/      # Middleware
   └── Models/          # Modelos
   views/               # Plantillas
   routes/              # Rutas
   ```

2. **Crear controladores** en `app/Controllers/`:
   - Extienden de un controlador base
   - Manejan lógica de negocio

3. **Crear modelos** en `app/Models/`:
   - Manejan datos y lógica de base de datos
   - Usan `leafs/db`

4. **Crear vistas** en `views/`:
   - Plantillas PHP o Blade
   - Para respuestas HTML

5. **Definir rutas** en `routes/web.php` y `routes/api.php`:
   - Conectan URLs con controladores

---

## Qué Necesitas para Decidir Qué Módulos Son Necesarios

### Preguntas Clave

1. **¿Qué tipo de aplicación vas a construir?**
   - API-only (solo JSON) → No necesitas motor de vistas
   - Server-rendered views (HTML) → Necesitas motor de vistas (Blade/BareUI)
   - Híbrido (API + Views) → Necesitas ambos

2. **¿Necesitas base de datos?**
   - Sí → Necesitas módulo de base de datos (`leafs/db` u ORM alternativo)
   - No → No necesitas módulo de base de datos

3. **¿Necesitas autenticación?**
   - Sí → Necesitas módulo de autenticación o implementarlo con sesiones/JWT
   - No → No necesario

4. **¿Necesitas seguridad adicional?**
   - CSRF → Necesitas `leafs/anchor` o módulo CSRF específico
   - Validación → Necesitas módulo de validación
   - No → `leafs/anchor` (incluido en core) puede ser suficiente

5. **¿Necesitas caché?**
   - Sí → Necesitas módulo de caché
   - No → No necesario

6. **¿Necesitas colas/jobs?**
   - Sí → Necesitas módulo de colas
   - No → No necesario

---

## Matriz de Trazabilidad

| Declaración | Fuente(s) | Estado |
|-------------|-----------|--------|
| leafs/db para base de datos | analisis-requisitos-proyecto-inmobiliario.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| leafs/alchemy para dev tools | SETUP_COMPLETE.md | ✅ Confirmado |
| leafmvc es wrapper MVC para leaf | mvc-modules-scaffolding-explicacion.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| leafs/http para HTTP layer | mvc-modules-scaffolding-explicacion.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| leafs/anchor para seguridad | mvc-modules-scaffolding-explicacion.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| leafs/exception para excepciones | SETUP_COMPLETE.md | ✅ Confirmado |
| Estructura de carpetas MVC | mvc-modules-scaffolding-explicacion.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| Blade como motor de vistas | concepto-de-proyecto.md, evaluacion-ui-templates-leaf.md | ✅ Confirmado |
| Instalación vía Composer | mvc-modules-scaffolding-explicacion.md, integrar-mvc-y-decision-modulos.md | ✅ Confirmado |
| Session + JWT ready | SETUP_COMPLETE.md | ✅ Confirmado |
| PostgreSQL en Railway, MySQL en Docker | SETUP_COMPLETE.md, PROJECT_README.md | ✅ Confirmado |

---

## Recomendaciones

### Para MVP

✅ **Usar módulos básicos**
- `leafs/leaf` (core)
- `leafs/http` (HTTP layer)
- `leafs/db` (capa de base de datos)
- `leafs/anchor` (seguridad básica)
- `leafs/exception` (manejo de excepciones)
- `leafs/mvc` (wrapper MVC)
- Blade (motor de vistas)

### Para Railway

✅ **Minimizar módulos**
- Menos módulos = despliegue más ligero
- Solo instalar lo que se necesita realmente

### Para Futuro

⏳ **Evaluar módulos adicionales**
- Módulo de caché si es necesario
- Módulo de colas/workflows si se implementa
- Módulo de validación si se necesita

---

## Referencias a Documentos Fuente

1. **mvc-modules-scaffolding-explicacion.md** - MVC, Modules y App Scaffolding en Leaf PHP
2. **integrar-mvc-y-decision-modulos.md** - Integrar MVC en Leaf y decidir módulos necesarios
3. **analisis-requisitos-proyecto-inmobiliario.md** - Análisis de requisitos del proyecto
4. **concepto-de-proyecto.md** - Concepto general del proyecto
5. **SETUP_COMPLETE.md** - Documentación del setup completado con módulos instalados
6. **PROJECT_README.md** - README principal del proyecto con información de módulos
7. **composer.json** - Dependencias del proyecto
