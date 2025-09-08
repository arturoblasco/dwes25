# Usar imagen de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    nodejs \
    npm

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Habilitar módulo de reescritura en Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Instalar Node.js y npm
RUN npm install -g npm

# Exponer puerto 80 para Apache
EXPOSE 80

# Establecer el punto de entrada
CMD ["apache2-foreground"]
