# Use an official PHP image with PHP 5.6
FROM php:5.6-fpm

# Install necessary extensions for CodeIgniter
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libmcrypt-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# Install Nginx and other necessary tools
RUN apt-get install -y nginx

# Remove the default server definition to use your own
RUN rm /etc/nginx/sites-enabled/default

# Set the working directory to your project folder
WORKDIR /var/www/html

# Copy the CodeIgniter project into the container
COPY . /var/www/html/

# Expose the HTTP port for Nginx
EXPOSE 80
