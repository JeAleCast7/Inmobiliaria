# Usamos la imagen oficial de PHP versión 8.2 con el servidor Apache integrado, 
# la cual concuerda con tu versión local en XAMPP.
FROM php:8.2-apache

# Habilitamos mod_rewrite de Apache, necesario si utilizas o utilizarás 
# URLs amigables (ej. con archivos .htaccess).
RUN a2enmod rewrite

# Actualizamos los repositorios e instalamos las dependencias del sistema 
# requeridas por las extensiones de PHP (ej. para procesar imágenes).
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    # Limpieza de caché de apt para reducir el tamaño de la imagen final
    && rm -rf /var/lib/apt/lists/*

# Configuramos e instalamos extensiones de PHP.
# - 'mysqli' es esencial para las conexiones nativas que estás utilizando a MariaDB/MySQL.
# - 'pdo' y 'pdo_mysql' se dejan instaladas como buena práctica por si migras a PDO en el futuro.
# - 'gd' es para el procesamiento de imágenes (muy útil en inmobiliarias para subir fotos de propiedades).
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli gd

# Copiamos todo el código fuente del directorio actual en la máquina host
# al directorio raíz por defecto del servidor web de Apache.
COPY . /var/www/html/

# Modificamos los permisos del código fuente para que el usuario del servidor (www-data)
# tenga los derechos necesarios para leer y ejecutar la aplicación (y escribir si tienes subida de archivos).
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponemos el puerto 80 del contenedor, por el cual Apache servirá el contenido.
EXPOSE 80

# El comando de arranque que mantienen el servicio Apache corriendo en primer plano.
CMD ["apache2-foreground"]
