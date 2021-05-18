FROM alpine:3.10

RUN apk add --update --no-cache \
    coreutils \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xdebug \
    php7-zip \
    php7-xmlwriter \
    php7-bcmath \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xmlreader \
    make \
    curl \
    git

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

COPY php.ini /etc/php7/conf.d/
COPY php.ini /etc/php7/cli/conf.d/

COPY phpfpm.pool.conf /etc/php7/php-fpm.d/

RUN apk add --update --no-cache \
    bash \
    mc \
    htop \
    openssl \
    zlib-dev \
    unzip \
    sudo \
    build-base \
    shadow

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod 755 /usr/local/bin/docker-entrypoint.sh \
    && ln -s /usr/local/bin/docker-entrypoint.sh /

RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN composer require symfony/maker-bundle --dev

RUN sed -i -e 's/^;zend_extension/\zend_extension/g' /etc/php7/conf.d/xdebug.ini

#Строчка, если не загружается проект в браузере. Исправляет ошибку 'standard_init_linux.go:211: exec user process caused "no such file or directory"'
#RUN chmod +x docker-entrypoint.sh

#Решение проблемы с правами
RUN chmod 777 -R /tmp

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["php-fpm7", "-F"]

WORKDIR /var/www/html

EXPOSE 9001