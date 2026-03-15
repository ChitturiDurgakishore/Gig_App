# Use PHP 8.2
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Node.js (needed for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Install JS dependencies
RUN npm install

# Build frontend assets (CSS/JS)
RUN npm run build

# Clear Laravel caches
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true

# Run migrations automatically (for Render free plan)
RUN php artisan migrate --force || true

# Expose port
EXPOSE 10000

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=10000
