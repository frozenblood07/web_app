FROM ubuntu:16.04

RUN apt-get update && \
    apt-get dist-upgrade -y && \
    apt-get install -y \
      apache2 \
      php7.0 \
      php7.0-cli \
      libapache2-mod-php7.0 \
      php7.0-gd \
      php7.0-json \
      php7.0-ldap \
      php7.0-mbstring \
      php7.0-mysql \
      php7.0-pgsql \
      php7.0-sqlite3 \
      php7.0-xml \
      php7.0-xsl \
      php7.0-zip \
      php7.0-soap \
      php7.0-curl 

COPY  /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile

RUN apt-get install -y supervisor
RUN mkdir -p /var/run/apache2 /var/run/sshd /var/log/supervisor

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apt-get update \
    && apt-get install pkg-config libmemcached-dev -y \
    && pecl install redis \
    && echo "extension=redis.so" > /etc/php/7.0/mods-available/redis.ini \
    && phpenmod -v 7.0 -s ALL redis \

RUN apt-get clean all \
    && rm -rf /var/lib/apt/lists/* 

RUN PHP_ERROR_REPORTING=${PHP_ERROR_REPORTING:-"E_ALL"}
RUN sed -ri 's/^display_errors\s*=\s*Off/display_errors = On/g' /etc/php/7.0/apache2/php.ini
RUN sed -ri 's/^display_errors\s*=\s*Off/display_errors = On/g' /etc/php/7.0/cli/php.ini
RUN sed -ri "s/^error_reporting\s*=.*$//g" /etc/php/7.0/apache2/php.ini
RUN sed -ri "s/^error_reporting\s*=.*$//g" /etc/php/7.0/cli/php.ini
RUN echo "error_reporting = $PHP_ERROR_REPORTING" >> /etc/php/7.0/apache2/php.ini
RUN echo "error_reporting = $PHP_ERROR_REPORTING" >> /etc/php/7.0/cli/php.ini
RUN sed -i 's~/var/www~/var/www/public~g' /etc/apache2/sites-enabled/000-default.conf

RUN apt-get -y install python-setuptools
RUN apt-get clean all

CMD ["/usr/bin/supervisord"]
