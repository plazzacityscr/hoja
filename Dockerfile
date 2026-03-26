# ===========================================
# Leaf PHP - Production Dockerfile
# Optimized for Railway and production deployment
# Updated: Added Redis extension for session and cache storage
# ===========================================

# Stage 1: Build dependencies
FROM composer:latest AS builder

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock* ./

# Install dependencies (including dev for build tools)
RUN composer install --no-interaction --no-progress

# Stage 2: Production image
FROM php:8.2-cli

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV APP_ENV=production
ENV APP_DEBUG=false

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    default-libmysqlclient-dev \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    opcache \
    redis

# Configure OPcache for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=60" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u 1000 -d /home/app app
RUN mkdir -p /home/app/.composer && \
    chown -R app:app /home/app

# Set working directory
WORKDIR /app

# Copy application files
COPY --chown=app:app . /app

# Copy composer dependencies from builder
COPY --from=builder --chown=app:app /app/vendor /app/vendor

# Create necessary directories
RUN mkdir -p storage/logs storage/uploads cache views && \
    chmod -R 775 storage cache && \
    chown -R app:app storage cache

# Create entrypoint script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Set PORT for Railway compatibility\n\
export PORT=${PORT:-8080}\n\
\n\
# Run migrations if they exist\n\
if [ -f "migrate.php" ]; then\n\
    echo "Running database migrations..."\n\
    php migrate.php || true\n\
fi\n\
\n\
# Start PHP built-in server\n\
echo "Starting Leaf PHP on port $PORT..."\n\
exec php -S "0.0.0.0:$PORT" -t public\n\
' > /usr/local/bin/entrypoint && \
    chmod +x /usr/local/bin/entrypoint

# Switch to non-root user
USER app

# Expose port (Railway uses $PORT env var)
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:${PORT:-8080}/health || exit 1

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint"]
