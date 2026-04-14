#!/bin/bash

# ===========================================
# Leaf PHP - Setup Script
# ===========================================
# Ejecuta este script para configurar el proyecto
# ===========================================

echo ""
echo "🍃 Leaf PHP - Setup Inicial"
echo "============================================"
echo ""

# Verificar PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP no está instalado. Por favor instala PHP 7.4+"
    exit 1
fi

PHP_VERSION=$(php -v | head -n 1)
echo "✓ PHP: $PHP_VERSION"

# Verificar Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer no está instalado. Por favor instala Composer"
    exit 1
fi

COMPOSER_VERSION=$(composer --version)
echo "✓ Composer: $COMPOSER_VERSION"

echo ""
echo "Instalando dependencias..."
echo "-------------------------------------------"
composer install --no-interaction

if [ $? -ne 0 ]; then
    echo "❌ Error instalando dependencias"
    exit 1
fi

echo "✓ Dependencias instaladas"

echo ""
echo "Configurando entorno..."
echo "-------------------------------------------"

if [ ! -f .env ]; then
    cp .env.example .env
    echo "✓ Archivo .env creado"
else
    echo "✓ Archivo .env ya existe"
fi

echo ""
echo "Generando APP_KEY..."
echo "-------------------------------------------"

# Generar APP_KEY si está vacío
if grep -q "APP_KEY=$" .env 2>/dev/null || ! grep -q "APP_KEY" .env 2>/dev/null; then
    APP_KEY=$(openssl rand -hex 32)
    if grep -q "APP_KEY=" .env 2>/dev/null; then
        sed -i "s/APP_KEY=.*/APP_KEY=$APP_KEY/" .env
    else
        echo "APP_KEY=$APP_KEY" >> .env
    fi
    echo "✓ APP_KEY generado"
else
    echo "✓ APP_KEY ya configurado"
fi

echo ""
echo "Creando directorios..."
echo "-------------------------------------------"

mkdir -p storage/logs storage/uploads storage/database cache views/errors
touch storage/logs/.gitkeep storage/uploads/.gitkeep storage/database/.gitkeep cache/.gitkeep views/.gitkeep

echo "✓ Directorios creados"

echo ""
echo "============================================"
echo "✅ Setup completado!"
echo ""
echo "Próximos pasos:"
echo "  1. Configura tu base de datos en .env"
echo "  2. Ejecuta: composer db:fresh (migrar + seed)"
echo "  3. Ejecuta: composer serve"
echo "  4. Visita: http://localhost:8000"
echo ""
echo "Comandos útiles:"
echo "  composer db:migrate  - Ejecutar migraciones"
echo "  composer db:seed     - Ejecutar seeders"
echo "  composer db:fresh    - Fresh migration + seed"
echo "  composer serve       - Iniciar servidor"
echo "============================================"
echo ""
