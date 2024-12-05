FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    libzip-dev \
    libmagickwand-dev \
    gnupg2 \
    rsync \
    vim \
    ca-certificates \
    wget

RUN pecl install redis

RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    opcache \
    zip

RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install gd

RUN docker-php-ext-enable redis

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Copy PHP config
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
