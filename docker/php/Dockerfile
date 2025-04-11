FROM composer:2.8.3 AS composer

FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

ARG UID=1000
ARG GID=1000

# Create user/group with minimal permissions necessary
RUN addgroup -S -g ${GID} user && \
    adduser -S -D -u ${UID} -G user user && \
    mkdir -p /app && \
    chown -R user:user /app

# Install system dependencies
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

WORKDIR /app

ARG UID=1000
ARG GID=1000
ENV PATH="$PATH:/app/bin"

# Create user and set up permissions in one layer
RUN addgroup -S -g ${GID} user && \
    adduser -S -D -u ${UID} -G user user && \
    mkdir -p /app && \
    chown -R user:user /app && \
    # Install required packages
    apk add --no-cache \
    curl \
    bash \
    git \
    libzip

# Copy PHP extensions and configs
COPY --from=builder --chown=user:user /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder --chown=user:user /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Install Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Switch to non-root user for remaining operations
USER user

COPY --chown=user:user ./app/php .

FROM base AS dev

ENV APP_ENV=dev
ENV APP_DEBUG=1