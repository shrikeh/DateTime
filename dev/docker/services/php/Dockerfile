ARG PHP_IMAGE_APP_VERSION="cli-alpine3.19"

FROM "php:${PHP_IMAGE_APP_VERSION}" as base
RUN apk add --update gcc autoconf icu-dev yaml-dev linux-headers
RUN apk add --no-cache --virtual .phpize-deps ${PHPIZE_DEPS}
RUN pecl install -o -f pcntl posix yaml pcov
RUN docker-php-ext-configure intl
RUN docker-php-ext-install bcmath intl
RUN docker-php-ext-enable opcache bcmath yaml pcov

FROM base AS composer
WORKDIR /composer
ADD dev/docker/services/php/composer.sh ./
RUN chmod +x ./composer.sh
RUN ./composer.sh

FROM base AS phpcov
WORKDIR /phpcov
RUN apk add --update wget
RUN wget 'https://phar.phpunit.de/phpcov.phar'

FROM base as shrikeh
ARG LOCAL_BIN_PATH=/usr/local/bin
ARG GITHUB_TOKEN
ENV APP_ENV=dev

COPY --from=composer /composer/composer.phar "${LOCAL_BIN_PATH}/composer"
RUN chmod +x "${LOCAL_BIN_PATH}/composer"

RUN apk add git openssh

WORKDIR /Shrikeh/DateTime

# Copy in only the files and directories needed for the app from the working directory
ADD ./.env ./
ADD ./composer.json ./
ADD ./composer.lock ./
ADD ./src ./src

RUN ${LOCAL_BIN_PATH}/composer config --global --auth github-oauth.github.com ${GITHUB_TOKEN}
RUN ${LOCAL_BIN_PATH}/composer install

# Copy only the files from before, leaving before dependencies such as composer and git
FROM base as app
ARG LOCAL_BIN_PATH=/usr/local/bin

COPY --from=phpcov /phpcov/phpcov.phar "${LOCAL_BIN_PATH}/phpcov"
RUN chmod +x "${LOCAL_BIN_PATH}/phpcov"

COPY --from=composer /composer/composer.phar "${LOCAL_BIN_PATH}/composer"
RUN chmod +x "${LOCAL_BIN_PATH}/composer"

COPY --from=shrikeh /root/.composer/ /root/.composer/

WORKDIR /cache
VOLUME /cache

RUN chown root:root /cache
RUN chmod 0777 /cache

WORKDIR /Shrikeh/DateTime
VOLUME /Shrikeh/DateTime
COPY --from=shrikeh /Shrikeh/DateTime /Shrikeh/DateTime

RUN composer validate
RUN chown -R www-data:www-data /Shrikeh/DateTime

FROM app as datetime
ENV GITHUB_TOKEN="${GITHUB_TOKEN}"
RUN pecl install xdebug
RUN apk add git
RUN docker-php-ext-enable xdebug
ADD dev/docker/services/php/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ENTRYPOINT /usr/local/bin/composer test
