FROM phusion/baseimage
MAINTAINER Christopher Reeves <chris@ternio.io>

ARG APP_ENV

RUN apt-get update && apt-get -y install wget postgresql postgresql-contrib libpq-dev \
vim lsof git-core default-jre curl zlib1g-dev \
build-essential libssl-dev libreadline-dev libyaml-dev libsqlite3-dev sqlite3 libxml2-dev libxslt1-dev \
libcurl4-openssl-dev python-software-properties libffi-dev libpq-dev

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs

RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get -y install nginx php7.2-cli php7.2-curl php7.2-fpm php7.2-gd php7.2-intl php7.2-json \
 php7.2-mysql php7.2-readline php7.2-tidy php7.2-xml php7.2-mbstring php7.2-bcmath php7.2-bz2 \
php7.2-imap php7.2-zip php7.2-soap php-pear php-apcu php-memcached php-geoip php-redis php-solr php-mongodb \
php7.2-pgsql php7.2-opcache php-zmq php-stomp php-imagick composer iputils-ping;

RUN if [ "$APP_ENV" = "local" ]; then \
    apt-get install -y php-xdebug; \
fi;

ADD . /www
WORKDIR /www

RUN chmod 775 docker/www/start.sh
CMD ["docker/www/start.sh"]
