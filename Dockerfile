FROM php:7.4-fpm

# Copy composer.lock and composer.json into the working directory
# COPY composer.lock composer.json /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev pkg-config libssl-dev \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    libzip-dev libgmp-dev \
    unzip \
    git \
    libonig-dev \
    curl \
    supervisor \
    libgmp-dev \
    curl \
    nodejs npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions for php
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gmp
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Add and Enable Redis extension
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Add and enable mongodb extension
RUN pecl install -o -f mongodb \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable mongodb

# Install Composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 laravel && \
    useradd -l -u 1000 -g laravel -m laravel && \
    usermod -p "*" laravel -s /bin/bash
RUN usermod -o -u 1000 www-data
RUN groupmod -o -g 1000 www-data
COPY .docker/docker-entrypoint.sh /usr/local/bin/start
RUN chmod 755 /usr/local/bin/start
RUN npm config set cache /home/laravel/.npm --global

# Copy existing application directory contents
# COPY .docker/supervisord.conf /etc/supervisord.conf
# COPY --chown=www-data:www-data . /var/www/html
RUN chown -R www-data:www-data /var/www/html/

USER www-data

CMD ["/usr/local/bin/start"]
