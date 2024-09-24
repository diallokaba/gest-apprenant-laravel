# Utiliser une image de base PHP avec les extensions nécessaires
FROM php:8.1-fpm

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    zip \
    unzip \
    zlib1g-dev \
    libzip-dev

# Installer Composer
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

# Installer les extensions PHP nécessaires à Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd \
    && docker-php-ext-install zip

# Installer et configurer le protocole gRPC
RUN pecl install grpc && docker-php-ext-enable grpc

# Mettre à jour Composer
RUN composer self-update --2

# Configurer l'environnement de travail
WORKDIR /var/www

# Copier les fichiers du projet Laravel dans le conteneur
COPY . .

# Installer les dépendances du projet Laravel
RUN composer install --no-dev --optimize-autoloader

# Copier les fichiers de configuration nécessaires
COPY .env.example .env

# Générer la clé de l'application
RUN php artisan key:generate

# Donner les permissions nécessaires aux répertoires de stockage et de cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Commande pour démarrer PHP-FPM
CMD ["php-fpm"]



