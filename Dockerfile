# Use an official PHP runtime as a parent image
FROM php:8.2-apache

# Install required system packages and dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . /var/www/html

# Adding Postgres support:
RUN docker-php-ext-install pdo_pgsql

# Copy custom Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite

# Set Apache to bind to IP address
# RUN echo "Listen 0.0.0.0:80" >> /etc/apache2/ports.conf

# Optionally, you can set environment variables
# ENV VARIABLE_NAME=value

# Expose port 80 to allow incoming connections
EXPOSE 80