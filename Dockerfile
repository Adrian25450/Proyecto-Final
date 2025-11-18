# Dockerfile para el entorno de PHP y Apache

# Usar una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite de Apache para URLs amigables (si es necesario)
RUN a2enmod rewrite

# Copiar los archivos de la aplicación al directorio de Apache
COPY ./backend /var/www/html/backend
COPY ./frontend /var/www/html/frontend
COPY ./index.html /var/www/html/index.html

# Establecer permisos correctos para la carpeta de uploads
RUN mkdir -p /var/www/html/backend/uploads && \
    chown -R www-data:www-data /var/www/html/backend/uploads && \
    chmod -R 775 /var/www/html/backend/uploads

# Exponer el puerto 80
EXPOSE 80
