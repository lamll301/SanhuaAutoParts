FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    postgresql \
    postgresql-client \
    zip \
    unzip \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . .

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Create supervisor configuration
RUN echo '[supervisord]\n\
nodaemon=true\n\
\n\
[program:laravel-worker]\n\
process_name=%(program_name)s_%(process_num)02d\n\
command=php /var/www/artisan queue:work --sleep=3 --tries=3 --max-time=3600\n\
autostart=true\n\
autorestart=true\n\
stopasgroup=true\n\
killasgroup=true\n\
user=www-data\n\
numprocs=1\n\
redirect_stderr=true\n\
stdout_logfile=/var/www/storage/logs/worker.log\n\
stopwaitsecs=3600\n\
\n\
[program:laravel-server]\n\
command=php /var/www/artisan serve --host=0.0.0.0 --port=%(ENV_PORT)s\n\
autostart=true\n\
autorestart=true\n\
user=www-data\n\
redirect_stderr=true\n\
stdout_logfile=/var/www/storage/logs/server.log' > /etc/supervisor/conf.d/laravel.conf

# Create init script
RUN echo '#!/bin/sh\n\
php artisan storage:link\n\
\n\
php artisan migrate:fresh --seed --force\n\
' > /var/www/init.sh \
&& chmod +x /var/www/init.sh

# Create start script
RUN echo '#!/bin/sh\n\
export PORT=${PORT:-8000}\n\
# Run init script first\n\
/var/www/init.sh\n\
# Set environment variable for supervisor\n\
export ENV_PORT=$PORT\n\
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf' > /var/www/start.sh \
&& chmod +x /var/www/start.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 8000
EXPOSE 8000

# Start using the script
CMD ["/var/www/start.sh"]
