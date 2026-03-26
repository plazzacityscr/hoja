# Leaf PHP Framework - Analysis & Adoption Plan

**Document Generated:** March 23, 2026  
**Repository:** https://github.com/leafsphp/leaf  
**Analysis Scope:** Full repository evaluation for production adoption

---

## Executive Summary

This document provides a comprehensive analysis of the Leaf PHP framework repository cloned into `/workspaces/hoja/leaf`. The repository is the **core Leaf framework library** itself, NOT a ready-to-deploy application. This is a **critical distinction** that affects the entire adoption strategy.

**Key Finding:** Leaf is a **microframework package** meant to be installed via Composer in a separate project. It requires additional setup to become a deployable application.

---

## 1. Technology Stack Analysis

### Core Stack
| Component | Version/Details |
|-----------|-----------------|
| **Language** | PHP 7.4 - 8.4 (tested across all versions) |
| **Framework Type** | Microframework (library package) |
| **License** | MIT |
| **Autoloading** | PSR-4 (`Leaf\` namespace) |
| **Testing** | Pest PHP with parallel execution |
| **Code Style** | PHP-CS-Fixer (PSR-12 preset) |
| **CI/CD** | GitHub Actions |

### Dependencies (composer.json)

**Production:**
```json
{
  "php": "^7.4|^8.0",
  "leafs/http": "*",
  "leafs/anchor": "*",
  "leafs/exception": "*"
}
```

**Development:**
```json
{
  "friendsofphp/php-cs-fixer": "^3.64",
  "pestphp/pest": "*",
  "leafs/alchemy": "^4.0"
}
```

### Leaf Ecosystem Dependencies
- `leafs/http` - HTTP request/response handling
- `leafs/anchor` - Security utilities (CSRF, validation)
- `leafs/exception` - Exception handling
- `leafs/alchemy` - Development tooling (linting, testing automation)

---

## 2. Repository Structure

```
/workspaces/hoja/leaf/
├── src/
│   ├── App.php          # Main application class (extends Router)
│   ├── Router.php       # Routing engine (989 lines)
│   ├── Config.php       # Configuration singleton
│   └── functions.php    # Global helper functions
├── tests/
│   ├── app.test.php
│   ├── config.test.php
│   ├── container.test.php
│   ├── core.test.php
│   ├── functional.test.php
│   ├── middleware.test.php
│   └── view.test.php
├── .github/
│   ├── workflows/
│   │   ├── tests.yml    # Multi-PHP, multi-OS test matrix
│   │   └── lint.yml     # Auto-fix and commit style issues
│   ├── CODE_OF_CONDUCT.md
│   ├── CONTRIBUTING.md
│   └── FUNDING.yml
├── alchemy.yml          # Alchemy tool configuration
├── composer.json
├── LICENSE
└── README.md
```

### Missing Files for Production Deployment
- ❌ No `index.php` entry point
- ❌ No `.env` or `.env.example`
- ❌ No `Dockerfile` or docker-compose
- ❌ No `phpunit.xml` or `pest.php` (uses alchemy defaults)
- ❌ No `public/` directory structure
- ❌ No `views/` directory
- ❌ No `storage/` or `cache/` directories
- ❌ No deployment configuration (Railway, Heroku, etc.)
- ❌ No `composer.lock` (expected for library packages)

---

## 3. System Requirements

### Minimum Requirements
| Requirement | Version |
|-------------|---------|
| PHP | 7.4 or higher |
| PHP Extensions | json, zip |
| Composer | v2+ |
| Web Server | Apache/Nginx with PHP-FPM OR built-in PHP server |

### Recommended for Development
- PHP 8.2+
- Xdebug (for debugging)
- Git

### Recommended for Production
- PHP 8.2+ with OPcache
- Nginx or Apache with mod_php/PHP-FPM
- SSL/TLS termination
- Proper file permissions for cache/logs

---

## 4. Environment Variables

The framework uses `_env()` helper function to read environment variables. Based on code analysis:

### Required/Expected Variables
| Variable | Default | Description |
|----------|---------|-------------|
| `APP_ENV` | `development` | Application mode (development/production/testing) |
| `APP_DEBUG` | `true` | Enable/disable debug mode |

### Optional Variables (via Config)
| Variable | Usage |
|----------|-------|
| Any custom | Passed via `app()->config()` or settings array |

### Missing .env File
**Action Required:** Create `.env.example` template with all configurable options.

---

## 5. Database & Migrations

### Current Status
- ❌ **No built-in database layer** in core Leaf
- ❌ **No migration system** in core
- ❌ **No ORM integration** by default

### Leaf Ecosystem Options
Based on documentation, Leaf offers optional modules:
- `leafs/db` - Database abstraction (separate package)
- `leafs/orm` - ORM layer (separate package)

### Recommendation
Database layer must be added as a separate decision:
1. Use Leaf's official `leafs/db` module
2. Use PDO directly
3. Integrate third-party ORM (Eloquent, Doctrine)

---

## 6. Scripts Analysis (composer.json)

| Script | Command | Purpose |
|--------|---------|---------|
| `test` | `./vendor/bin/alchemy setup --test` | Run Pest tests |
| `alchemy` | `./vendor/bin/alchemy setup` | Run Alchemy setup |
| `lint` | `./vendor/bin/alchemy setup --lint` | Run PHP-CS-Fixer |
| `actions` | `./vendor/bin/alchemy setup --actions` | Generate GitHub Actions |

**Note:** All scripts depend on `leafs/alchemy` dev dependency.

---

## 7. Deployment Readiness Assessment

### Current State: **NOT PRODUCTION READY**

| Aspect | Status | Gap |
|--------|--------|-----|
| Entry Point | ❌ Missing | Need `public/index.php` |
| Environment Config | ❌ Missing | Need `.env` system |
| Error Handling | ✅ Built-in | Configurable via `setErrorHandler()` |
| Logging | ⚠️ Optional | Requires external logger module |
| Security (CSRF) | ⚠️ Optional | Requires `leafs/csrf` module |
| View Rendering | ⚠️ Optional | Requires Blade/BareUI module |
| Asset Pipeline | ⚠️ Optional | Vite integration available |
| Docker Support | ❌ Missing | Need Dockerfile |
| CI/CD | ✅ Configured | GitHub Actions for tests/lint |
| Health Checks | ❌ Missing | Need implementation |

---

## 8. Installation & Setup Guide

### Local Development Setup

#### Step 1: Install Dependencies
```bash
cd /workspaces/hoja/leaf
composer install
```

#### Step 2: Run Tests (Verify Installation)
```bash
composer run test
```

#### Step 3: Run Linter
```bash
composer run lint
```

#### Step 4: Create Test Application
Since this is a library, create a separate application to use it:

```bash
# Create project structure
mkdir -p public views storage/logs cache

# Create public/index.php
cat > public/index.php << 'EOF'
<?php

require __DIR__ . '/../vendor/autoload.php';

app()->get('/', function () {
    response()->json([
        'message' => 'Hello World!'
    ]);
});

app()->run();
EOF
```

#### Step 5: Start Development Server
```bash
# Option A: PHP built-in server
cd public && php -S localhost:8000

# Option B: Leaf CLI (if installed globally)
leaf serve
```

---

## 9. Implementation Plan

### Phase 1: Foundation Setup (Priority: CRITICAL)
**Goal:** Make the repository usable as a project base

| Task | Description | Effort | Can Automate |
|------|-------------|--------|--------------|
| 1.1 | Create `public/index.php` entry point | Low | ✅ Yes |
| 1.2 | Create `.env.example` template | Low | ✅ Yes |
| 1.3 | Create directory structure (`views/`, `storage/`, `cache/`) | Low | ✅ Yes |
| 1.4 | Create `.gitignore` for project files | Low | ✅ Yes |
| 1.5 | Create basic `composer.json` for application (not library) | Medium | ✅ Yes |

### Phase 2: Development Environment (Priority: HIGH)
**Goal:** Enable productive local development

| Task | Description | Effort | Can Automate |
|------|-------------|--------|--------------|
| 2.1 | Create Dockerfile for development | Medium | ✅ Yes |
| 2.2 | Create docker-compose.yml with PHP + optional services | Medium | ✅ Yes |
| 2.3 | Configure Xdebug for debugging | Low | ✅ Yes |
| 2.4 | Create VS Code/IDE configuration | Low | ✅ Yes |
| 2.5 | Set up hot-reload for development | Medium | ⚠️ Partial |

### Phase 3: Core Features (Priority: HIGH)
**Goal:** Add essential application features

| Task | Description | Effort | Can Automate |
|------|-------------|--------|--------------|
| 3.1 | Integrate database layer (decision required) | Medium | ⚠️ Partial |
| 3.2 | Create migration system | Medium | ⚠️ Partial |
| 3.3 | Set up view engine (Blade recommended) | Low | ✅ Yes |
| 3.4 | Configure CSRF protection | Low | ✅ Yes |
| 3.5 | Set up logging system | Low | ✅ Yes |
| 3.6 | Create base controller structure | Medium | ✅ Yes |

### Phase 4: Production Readiness (Priority: MEDIUM)
**Goal:** Prepare for deployment

| Task | Description | Effort | Can Automate |
|------|-------------|--------|--------------|
| 4.1 | Create production Dockerfile | Medium | ✅ Yes |
| 4.2 | Configure OPcache and optimizations | Low | ✅ Yes |
| 4.3 | Set up health check endpoint | Low | ✅ Yes |
| 4.4 | Create deployment scripts | Medium | ✅ Yes |
| 4.5 | Configure error pages | Low | ✅ Yes |
| 4.6 | Set up monitoring hooks | Medium | ⚠️ Partial |

### Phase 5: Railway Deployment (Priority: MEDIUM)
**Goal:** Deploy to Railway.com

| Task | Description | Effort | Can Automate |
|------|-------------|--------|--------------|
| 5.1 | Create `railway.json` or `Dockerfile` | Low | ✅ Yes |
| 5.2 | Configure environment variables in Railway | Low | ⚠️ Manual |
| 5.3 | Set up Railway database addon (if needed) | Low | ⚠️ Manual |
| 5.4 | Configure custom domain (if needed) | Low | ⚠️ Manual |
| 5.5 | Test deployment pipeline | Medium | ✅ Yes |

---

## 10. Technical Roadmap

### Immediate Actions (Can be automated now)

```
┌─────────────────────────────────────────────────────────────┐
│  SPRINT 0: Project Scaffolding                              │
├─────────────────────────────────────────────────────────────┤
│  ✓ Create public/index.php                                  │
│  ✓ Create .env.example                                      │
│  ✓ Create directory structure                               │
│  ✓ Create application composer.json                         │
│  ✓ Create Dockerfile                                        │
│  ✓ Create docker-compose.yml                                │
│  ✓ Create .gitignore (project-specific)                     │
│  ✓ Create README.md for project                             │
│  ✓ Create railway.json                                      │
└─────────────────────────────────────────────────────────────┘
```

### Short-term (Week 1-2)

```
┌─────────────────────────────────────────────────────────────┐
│  SPRINT 1: Core Infrastructure                              │
├─────────────────────────────────────────────────────────────┤
│  → Database integration decision & implementation           │
│  → Migration system setup                                   │
│  → View engine configuration                                │
│  → Authentication scaffold                                  │
│  → Base controller structure                                │
└─────────────────────────────────────────────────────────────┘
```

### Medium-term (Week 3-4)

```
┌─────────────────────────────────────────────────────────────┐
│  SPRINT 2: Production Hardening                             │
├─────────────────────────────────────────────────────────────┤
│  → Caching layer (Redis/Memcached)                          │
│  → Queue system for background jobs                         │
│  → API rate limiting                                        │
│  → Security hardening                                       │
│  → Monitoring & logging setup                               │
└─────────────────────────────────────────────────────────────┘
```

---

## 11. Operational Roadmap (Minimizing User Intervention)

### What I Can Do Automatically ✅

1. **File Creation:** All scaffolding files, configurations, templates
2. **Code Generation:** Base controllers, models, routes, middleware
3. **Configuration:** Docker, CI/CD, environment templates
4. **Testing:** Write test suites, configure Pest
5. **Documentation:** Generate README, API docs, deployment guides
6. **Security:** Configure CSRF, security headers, best practices
7. **Optimization:** PHP OPcache, composer autoload optimization

### What Can Be Automated ⚠️

1. **Database Setup:** Schema creation scripts (needs DB choice)
2. **Migration Generation:** Once ORM/DB layer is chosen
3. **Asset Pipeline:** Vite configuration (needs frontend choice)
4. **Deployment Scripts:** Can prepare, needs credentials

### What Requires User Decision ❌

1. **Database Layer Choice:** PDO vs Eloquent vs Doctrine vs leafs/db
2. **View Engine:** Blade vs BareUI vs Twig vs none (API-only)
3. **Authentication:** Custom vs Sentry vs JWT vs OAuth
4. **External Services:** Email provider, storage (S3), etc.
5. **Railway Credentials:** Account, project creation, addon setup
6. **Domain Configuration:** Custom domain vs Railway subdomain
7. **Business Logic:** Application-specific features

---

## 12. Railway Deployment Strategy

### Railway.com Compatibility Assessment

| Requirement | Status | Notes |
|-------------|--------|-------|
| PHP Support | ✅ | Via Docker or Nixpacks |
| Environment Variables | ✅ | Railway dashboard |
| Database | ✅ | Railway PostgreSQL/MySQL addons |
| Persistent Storage | ⚠️ | Requires volume configuration |
| Custom Domains | ✅ | Railway feature |
| Auto-deploy on Push | ✅ | GitHub integration |

### Deployment Plan for Railway

#### Option A: Docker-based (Recommended)
```dockerfile
# Dockerfile optimized for Railway
FROM php:8.2-cli

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8080

# Start command
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
```

#### Option B: Nixpacks (Railway Native)
Create `nixpacks.toml`:
```toml
[phases.setup]
nixPkgs = ["php82Full", "composer"]

[phases.install]
cmds = ["composer install --no-dev --optimize-autoloader"]

[start]
cmd = "php -S 0.0.0.0:$PORT -t public"
```

#### Railway Configuration Steps

1. **Create Project on Railway:**
   - Go to https://railway.com
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose the `hoja/leaf` repository

2. **Configure Environment:**
   ```
   APP_ENV=production
   APP_DEBUG=false
   PORT=8080
   ```

3. **Add Database (if needed):**
   - Click "New" → "Database" → Choose PostgreSQL/MySQL
   - Railway auto-injects connection variables

4. **Configure Build:**
   - Set build command: `composer install --no-dev`
   - Set start command: `php -S 0.0.0.0:$PORT -t public`

5. **Deploy:**
   - Push to main branch triggers auto-deploy

---

## 13. Risk Assessment

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Leaf is a library, not app | High | Certain | Scaffold application layer |
| No official database layer | Medium | High | Evaluate and integrate early |
| Limited documentation | Medium | Medium | Rely on source code + community |
| Small community/ecosystem | Medium | Medium | Plan for self-sufficiency |
| Breaking changes in dependencies | Low | Low | Lock versions in composer.lock |
| Railway cost scaling | Low | Low | Monitor usage, set limits |

---

## 14. Decisions Requiring User Input

### Critical Decisions

1. **Project Structure Decision:**
   - Keep Leaf as a dependency in a new project?
   - OR fork/modify Leaf core directly?
   - **Recommendation:** Keep as dependency, create separate app structure

2. **Database Strategy:**
   - Option A: `leafs/db` (official, minimal)
   - Option B: Eloquent (full-featured, Laravel standard)
   - Option C: Doctrine (enterprise-grade)
   - Option D: Raw PDO (maximum control)
   - **Recommendation:** Eloquent for most use cases

3. **Application Type:**
   - Server-rendered views (Blade)?
   - API-only (JSON responses)?
   - Hybrid (Inertia.js)?
   - **Recommendation:** Depends on frontend requirements

4. **Railway Plan:**
   - Hobby ($5/month)?
   - Pro (usage-based)?
   - **Recommendation:** Start with Hobby, scale as needed

### Non-Critical Decisions (Can Defer)

- View engine choice (if API-only)
- Authentication method
- Caching strategy
- Queue system choice

---

## 15. Recommended Next Steps

### Immediate (I can execute now):

1. ✅ Create application scaffolding in `/workspaces/hoja/leaf`
2. ✅ Add `public/index.php` entry point
3. ✅ Add `.env.example` with all configurable options
4. ✅ Create Dockerfile for development and production
5. ✅ Create `docker-compose.yml` with PHP + optional services
6. ✅ Create `railway.json` for one-click deployment
7. ✅ Create comprehensive README with setup instructions

### Awaiting User Decision:

1. ❓ Confirm database layer preference
2. ❓ Confirm view engine preference (or API-only)
3. ❓ Confirm authentication requirements
4. ❓ Confirm Railway account availability

---

## Appendix A: File Templates to Create

### `.env.example`
```env
# Application
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (if using)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leaf_app
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync
```

### `public/index.php`
```php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Configure application
app()->config([
    'debug' => _env('APP_DEBUG', true),
    'mode' => _env('APP_ENV', 'development'),
]);

// Routes
app()->get('/', function () {
    response()->json([
        'message' => 'Welcome to Leaf PHP',
        'version' => '3.0'
    ]);
});

// Run application
app()->run();
```

---

## Appendix B: Commands Reference

### Development
```bash
# Install dependencies
composer install

# Run tests
composer run test

# Run linter
composer run lint

# Start dev server
php -S localhost:8000 -t public
```

### Production
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Clear cache
rm -rf cache/*

# Set permissions
chmod -R 755 storage cache
```

---

## Document Status

**Status:** Draft - Awaiting User Decisions  
**Next Review:** After scaffolding completion  
**Owner:** Development Team

---

*This document will be updated as the project evolves and decisions are made.*
