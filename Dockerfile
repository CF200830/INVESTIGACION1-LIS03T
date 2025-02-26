# Usar una imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y tambien extensiones de PHP
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && docker-php-ext-enable pdo pdo_mysql zip

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos de la API al contenedor
COPY . /var/www/html

# Cambiar permisos para Apache
RUN chown -R www-data:www-data /var/www/html

# Instalar dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 80 para el servidor web
EXPOSE 80

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]
