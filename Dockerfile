FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Install JS dependencies
RUN npm install

# Build frontend assets
RUN npm run build

# Clear caches
RUN php artisan config:clear || true
RUN php artisan cache:clear || true

EXPOSE 10000

# Run migrations when container starts
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000