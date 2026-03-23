# 🍃 Leaf PHP - Project Base

> **Elegant PHP for modern developers** - A complete project scaffold based on the Leaf PHP microframework.

[![Leaf PHP Version](https://poser.pugx.org/leafs/leaf/v/stable)](https://packagist.org/packages/leafs/leaf)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue.svg)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## 📋 Quick Start

### Prerequisites

- PHP 7.4 or higher
- Composer v2+
- (Optional) Docker & Docker Compose

### Installation

```bash
# Clone the repository
git clone <your-repo-url> my-app
cd my-app

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Start development server
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` to see your application running!

---

## 🏗️ Project Structure

```
leaf/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Middleware/      # Custom middleware
│   └── Models/          # Data models
├── config/              # Configuration files
├── docker/              # Docker configuration
│   ├── mysql/           # MySQL initialization
│   └── nginx/           # Nginx configuration
├── public/              # Public web root
│   └── index.php        # Application entry point
├── routes/              # Route definitions
│   ├── web.php          # Web routes
│   └── api.php          # API routes
├── storage/             # Application storage
│   ├── logs/            # Log files
│   └── uploads/         # User uploads
├── views/               # View templates
│   └── errors/          # Error pages
├── cache/               # Template cache
├── tests/               # Test files
├── .env.example         # Environment template
├── docker-compose.yml   # Docker Compose config
├── Dockerfile           # Production Dockerfile
├── Dockerfile.dev       # Development Dockerfile
└── railway.json         # Railway deployment config
```

---

## 🚀 Development

### Using PHP Built-in Server

```bash
php -S localhost:8000 -t public
```

### Using Docker Compose

```bash
# Start all services
docker-compose up -d

# Start with database
docker-compose --profile with-db up -d

# Start with all services (including nginx, redis, mailhog)
docker-compose --profile with-db --profile with-cache --profile with-mail up -d

# View logs
docker-compose logs -f app

# Enter container
docker-compose exec app bash
```

### Available Services (Docker)

| Service | Port | Description |
|---------|------|-------------|
| App | 8000 | PHP application |
| Nginx | 8080 | Web server (profile: with-nginx) |
| MySQL | 3306 | Database (profile: with-db) |
| PostgreSQL | 5432 | Alternative DB (profile: with-db) |
| Redis | 6379 | Cache (profile: with-cache) |
| phpMyAdmin | 8081 | MySQL admin (profile: with-db) |
| MailHog | 8025 | Email testing (profile: with-mail) |

---

## 🌍 Environment Variables

Copy `.env.example` to `.env` and configure:

```env
# Application
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leaf_app
DB_USERNAME=root
DB_PASSWORD=secret

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file

# Logging
LOG_DRIVER=file
LOG_LEVEL=debug
```

---

## 📡 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Welcome message |
| GET | `/health` | Health check |
| GET | `/api/info` | API information |
| GET | `/api/v1/health` | API v1 health |
| GET | `/api/v1/info` | API v1 info |
| POST | `/api/v1/echo` | Echo request (testing) |
| GET | `/api/v1/users` | List users |
| GET | `/api/v1/users/{id}` | Get user |
| POST | `/api/v1/users` | Create user |
| PUT | `/api/v1/users/{id}` | Update user |
| DELETE | `/api/v1/users/{id}` | Delete user |

---

## 🗄️ Base de Datos

Este proyecto utiliza **leafs/db** como capa de base de datos oficial.

### Configurar Base de Datos

1. Copia `.env.example` a `.env`
2. Configura las variables de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leaf_app
DB_USERNAME=root
DB_PASSWORD=secret
```

### Ejecutar Migraciones

```bash
# Ejecutar migraciones
php db.php migrate

# Ver estado
php db.php migrate:status

# Ejecutar seeders
php db.php seed
```

### Docker con Base de Datos

```bash
# Iniciar con MySQL
docker-compose --profile with-db up -d

# Migrar
docker-compose exec app php db.php migrate

# Seed
docker-compose exec app php db.php seed
```

Para más detalles, ver [DATABASE.md](DATABASE.md).

---

## 🧪 Testing

```bash
# Run tests
composer run test

# Run linter
composer run lint
```

---

## 📦 Deployment

### Railway

1. Push your code to GitHub
2. Go to [Railway](https://railway.com)
3. Click "New Project" → "Deploy from GitHub"
4. Select your repository
5. Add environment variables from `.env.example`
6. (Optional) Add a database: New → Database
7. Deploy!

Railway automatically detects the `Dockerfile` and deploys using it.

### Traditional Hosting

```bash
# Build for production
composer install --no-dev --optimize-autoloader

# Set permissions
chmod -R 755 storage cache
chown -R www-data:www-data storage cache

# Point web server to public/
```

### Docker Production

```bash
# Build image
docker build -t my-leaf-app .

# Run container
docker run -d -p 8080:8080 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  my-leaf-app
```

---

## 🔧 Configuration

### Adding Routes

Edit `routes/web.php` or `routes/api.php`:

```php
// Simple route
app()->get('/hello', function () {
    response()->json(['message' => 'Hello!']);
});

// Controller route
app()->get('/users', [UserController::class, 'index']);

// Route with middleware
app()->get('/admin', [
    'handler' => [AdminController::class, 'index'],
    'middleware' => 'auth'
]);

// Route group
app()->group('/api', function () {
    app()->get('/users', [UserController::class, 'index']);
    app()->post('/users', [UserController::class, 'store']);
});
```

### Adding Middleware

Create a middleware class in `app/Middleware/`:

```php
namespace App\Middleware;

class MyMiddleware
{
    public function call()
    {
        // Before route handler
        // ...
        
        // After route handler
    }
}
```

Register in `public/index.php`:

```php
app()->use(App\Middleware\MyMiddleware::class);
```

### Database Integration

Leaf usa **leafs/db** como capa de base de datos oficial.

```php
// En modelos
use Leaf\Db;

$db = db(); // Obtiene instancia de configuración
$users = $db->query("SELECT * FROM users");

// O usa el modelo User incluido
use App\Models\User;

$userModel = new User();
$users = $userModel->all();
$user = $userModel->find(1);
```

Ver [DATABASE.md](DATABASE.md) para detalles completos.

---

## 🛡️ Security

### CSRF Protection

Enable CSRF protection in your routes:

```php
app()->csrf([
    'exclude' => ['/api/*']
]);
```

### Security Headers

Add security middleware:

```php
app()->use(function () {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
});
```

---

## 📚 Resources

- [Leaf PHP Documentation](https://leafphp.dev)
- [Leaf Modules](https://leafphp.dev/modules/)
- [GitHub Repository](https://github.com/leafsphp/leaf)
- [Discord Community](https://discord.com/invite/Pkrm9NJPE3)

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `composer run test`
5. Run linter: `composer run lint`
6. Submit a pull request

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

---

## 🎯 Next Steps

1. **Configure your environment** - Copy `.env.example` to `.env`
2. **Set up database** - Choose and configure your preferred database layer
3. **Add your routes** - Edit `routes/web.php` and `routes/api.php`
4. **Build features** - Create controllers, models, and views
5. **Deploy** - Follow the deployment guide for Railway or your preferred platform

---

*Built with ❤️ using Leaf PHP*
