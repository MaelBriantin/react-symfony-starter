FROM composer:2.8.3 AS composer

FROM php:8.4-fpm-alpine AS builder

# Set the working directory
WORKDIR /var/www/php

# Set environment variables
ARG UID=1000
ARG GID=1000

# Create a non-root user and group
RUN addgroup -g $GID -S user \
    && adduser -u $UID -D -S -G user user

# Install system dependencies with correct package names
RUN apk add --no-cache \
    bash \
    curl \
    g++ \
    gcc \
    git \
    oniguruma-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    linux-headers \
    make \
    unzip \
    zip

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath

FROM php:8.4-fpm-alpine AS base

# Set the working directory
WORKDIR /var/www/php

# Set environment variables
ENV PATH="$PATH:/var/www/bin"

# Set environment variables
ARG UID=1000
ARG GID=1000

# Create a non-root user and group
RUN addgroup -g $GID -S user \
    && adduser -u $UID -D -S -G user user

# Copy PHP extensions and configs
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Install Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Install packages
RUN apk add --no-cache \
    curl \
    bash \
    git \
    libzip

# Create directory with correct permissions
RUN mkdir -p /var/www/php/vendor && \
    chown -R ${UID}:${GID} /var/www/php

# Copy composer files first for better caching
COPY --chown=${UID}:${GID} ./composer.* ./

# Switch to non-root user for all remaining operations
USER ${UID}:${GID}

# Install dependencies without scripts
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

# Copy the rest of the application
COPY --chown=${UID}:${GID} . ./

# Install all dependencies and run scripts
RUN composer install --prefer-dist --no-progress --no-interaction && \
    composer dump-autoload --optimize --classmap-authoritative

# Ensure correct permissions
RUN chmod -R 755 ./vendor
