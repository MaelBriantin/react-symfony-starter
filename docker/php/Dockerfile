# ----- Stage 1: Composer -----
FROM composer:2.8.3 AS composer

# ----- Stage 2: Builder stage for PHP extensions compilation -----
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# NOTE: User 'user' (UID 1000) and group 'user' (GID 1000) already exist in this image
# Created a new one with the same UID/GID resulting in an error
# Created a new one with different UID/GID resulting in impossible to run
# the composer install command at the end of the instaallation

# Install system dependencies for compilation (as root)
USER root
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

# Install PHP extensions (as root)
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath

# ----- Stage 3: Final base image -----
FROM php:8.4-fpm-alpine AS base

WORKDIR /app

# Ensure 'user' can write to /app in case it's not mounted
# and install runtime packages (as root)
USER root
RUN mkdir -p /app && \
    apk add --no-cache \
        curl \
        bash \
        git \
        libzip && \
    # Give ownership to existing user/group 'user'
    chown -R user:user /app

# Copy extensions and Composer, giving ownership to 'user'
# --chown=user:user will match the existing UID/GID 1000
COPY --from=builder --chown=user:user /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder --chown=user:user /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# Switch back to the EXISTING non-root 'user' for execution
# This user has UID/GID 1000, matching your host
USER user

# ----- Stage 4: Development image -----
FROM base AS dev
# Inherits USER user from previous stage
ENV APP_ENV=dev
ENV APP_DEBUG=1