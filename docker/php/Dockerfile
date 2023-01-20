FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        vim \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
	libpng-dev \
	libonig-dev \
	libzip-dev \
	libpq-dev \
	libicu-dev \
	libssl-dev \
        && docker-php-ext-install -j$(nproc)  pdo pdo_pgsql pgsql iconv mbstring mysqli pdo_mysql zip bcmath intl sockets \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd \
        && docker-php-ext-configure intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www

CMD composer install && php-fpm
