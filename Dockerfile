FROM php:8.2-apache

RUN a2enmod rewrite

# # Installer les dépendances système pour les extensions PHP et Composer
# # (git et unzip sont utiles pour composer ou des dépendances)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
# pdo_mysql est crucial pour se connecter à la base de données MySQL
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail DANS le conteneur
WORKDIR /var/www/html

# Copier composer.json et composer.lock et installer les dépendances
# Cela permet de mettre en cache cette étape si ces fichiers ne changent pas
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copier le reste du code de l'application dans le conteneur
COPY . .

# S'assurer que les répertoires nécessaires sont accessibles en écriture par Apache (www-data)
# Adaptez ces chemins si votre structure est différente
RUN mkdir -p src/views/cache && chown -R www-data:www-data src/views/cache
# Si vous avez des logs applicatifs:
# RUN mkdir -p var/logs && chown -R www-data:www-data var/logs

# Le DocumentRoot d'Apache est déjà /var/www/html par défaut dans cette image.
# Assurez-vous que votre application est structurée pour que public/index.php
# soit accessible via /var/www/html/public/index.php
# Le .htaccess dans public/ gérera la redirection.

# Apache est démarré automatiquement par l'image de base (CMD ["apache2-foreground"])