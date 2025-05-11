# ----- Stage 1: Composer -----
FROM composer:2.8.8 AS composer

# ----- Stage 2: Builder stage for PHP extensions compilation -----
FROM php:8.4-fpm-alpine AS builder

ARG UID=1000
ARG GID=1000

WORKDIR /app

# Create user 'user' (UID 1000) and group 'user' (GID 1000)
RUN addgroup -g $GID user && \
    adduser -u $UID -D -G user user

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

ARG UID=1000
ARG GID=1000

WORKDIR /app

# Create user 'user' (UID 1000) and group 'user' (GID 1000)
RUN addgroup -g $GID user && \
    adduser -u $UID -D -G user user

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

USER user

# ----- Stage 4: Development image -----
FROM base AS dev
# Inherits USER user from previous stage
ENV APP_ENV=dev
ENV APP_DEBUG=1