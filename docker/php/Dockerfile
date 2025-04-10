FROM composer:2.8.3 AS composer

FROM php:8.4-fpm-alpine AS builder

# Set the working directory
WORKDIR /app

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
WORKDIR /app

# Set environment variables
ENV PATH="$PATH:/app/bin"

# Set environment variables
ARG UID=1000
ARG GID=1000

# Create a non-root user and group
RUN addgroup -g $GID -S user \
    && adduser -u $UID -D -S -G user user

# Copy the non-root user and group from the composer stage
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Install packages
RUN apk add --no-cache \
    curl \
    bash \
    git \
    libzip

# Copy composer files first for better caching
COPY --chown=${UID}:${GID} app/php/composer.* ./

# Copy the rest of the application
COPY --chown=${UID}:${GID} app/php ./

# Install composer dependencies
RUN composer install --no-scripts

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R ${UID}:${GID} ./
