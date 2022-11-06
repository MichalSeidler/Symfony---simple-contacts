FROM php:8.1.0-apache
ENV ACCEPT_EULA=Y

RUN usermod -u 1000 www-data

RUN apt-get upgrade -y
RUN apt-get update && apt-get install -y wget cron supervisor gnupg curl locales gettext libc-client-dev libkrb5-dev libmcrypt-dev git zip unzip dialog apt-utils unixodbc
RUN docker-php-ext-install pdo pdo_mysql mysqli pcntl gettext

# Timezone
ENV TZ=Europe/Prague
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Locales CZ
RUN apt-get install -y locales
RUN sed -i '/cs_CZ/s/^# //g' /etc/locale.gen && locale-gen

ENV LC_ALL C.UTF-8
ENV LANG cs_CZ.UTF-8
ENV LANGUAGE cs_CZ.UTF-8

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli

# Yarn
RUN curl https://deb.nodesource.com/setup_16.x | bash
RUN curl https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y yarn

# Cron
COPY ./.docker/conf/supervisord.conf /etc/supervisor/
ADD ./.docker/conf/cron.conf /etc/cron.d/app
RUN chmod 0644 /etc/cron.d/*

# VHOSTS
COPY ./.docker/conf/vhost/local.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers

# Activate a2enmod
RUN a2enmod rewrite

# Copy sources
COPY ./ /var/www/html
RUN chown -R www-data:www-data /var/www
#RUN chmod +x /var/www/html/bin/cron/cron.sh

WORKDIR /var/www/html
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]