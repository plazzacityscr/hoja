# ===========================================
# Leaf PHP - Base de Datos y Migraciones
# ===========================================

## Configuración de Base de Datos

Este proyecto utiliza **leafs/db** como capa de base de datos oficial.

### Configuración en `.env`

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leaf_app
DB_USERNAME=root
DB_PASSWORD=secret

# Para SQLite (desarrollo rápido)
# DB_CONNECTION=sqlite
# DB_DATABASE=./storage/database/database.sqlite
```

## Migraciones

Las migraciones están en `database/migrations/`.

### Ejecutar Migraciones

```bash
# Ejecutar todas las migraciones pendientes
php db.php migrate

# Ver estado de migraciones
php db.php migrate:status

# Revertir última migración
php db.php migrate:rollback

# Revertir últimas N migraciones
php db.php migrate:rollback 3

# Fresh migration (revertir todo y migrar)
php db.php fresh

# Fresh + seeders
php db.php fresh:seed
```

### Crear Nueva Migración

```bash
# Crear archivo de migración
touch database/migrations/2024_01_02_000000_create_posts_table.php
```

Estructura de migración:

```php
<?php

namespace Database\Migrations;

use Leaf\Db;

class CreatePostsTable
{
    protected Db $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function up(): void
    {
        $this->db->query("
            CREATE TABLE posts (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                body TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down(): void
    {
        $this->db->query("DROP TABLE IF EXISTS posts");
    }
}
```

## Seeders

Los seeders están en `database/seeders/`.

### Ejecutar Seeders

```bash
# Ejecutar todos los seeders
php db.php seed

# Ejecutar seeder específico
php db.php seed:users
```

### Crear Nuevo Seeder

```bash
touch database/seeders/PostSeeder.php
```

Estructura de seeder:

```php
<?php

namespace Database\Seeders;

use Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->query(
            "INSERT INTO posts (title, body) VALUES (?, ?)",
            ['Post de prueba', 'Contenido del post']
        );
    }
}
```

## Modelo Base

Los modelos están en `app/Models/`.

### Uso del Modelo User

```php
use App\Models\User;

$userModel = new User();

// Obtener todos
$users = $userModel->all();

// Buscar por ID
$user = $userModel->find(1);

// Buscar por email
$user = $userModel->findByEmail('test@example.com');

// Crear
$userId = $userModel->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret123',
]);

// Actualizar
$userModel->update(1, ['name' => 'New Name']);

// Eliminar
$userModel->delete(1);
```

## Comandos Disponibles

```bash
php db.php migrate           # Ejecutar migraciones
php db.php migrate:rollback  # Revertir migraciones
php db.php migrate:status    # Ver estado
php db.php seed              # Ejecutar seeders
php db.php seed:users        # Seed de usuarios
php db.php fresh             # Fresh migration
php db.php fresh:seed        # Fresh + seeders
```

## Docker con Base de Datos

```bash
# Iniciar con MySQL
docker-compose --profile with-db up -d

# Acceder a MySQL
docker-compose exec mysql mysql -uleaf -psecret leaf_app

# Ver logs de MySQL
docker-compose logs mysql
```

## Endpoints de Usuario

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/users` | Listar usuarios |
| GET | `/api/v1/users/{id}` | Ver usuario |
| POST | `/api/v1/users` | Crear usuario |
| PUT | `/api/v1/users/{id}` | Actualizar usuario |
| DELETE | `/api/v1/users/{id}` | Eliminar usuario |

### Ejemplo: Crear Usuario

```bash
curl -X POST http://localhost:8000/api/v1/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123"
  }'
```

### Ejemplo: Listar Usuarios

```bash
curl http://localhost:8000/api/v1/users
```
