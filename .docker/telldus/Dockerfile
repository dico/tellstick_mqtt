FROM thecodingmachine/php:7.2-v2-apache

#RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev \
#	&& pecl install Mosquitto-alpha

COPY src/ /var/www/html/

#RUN chmod 777 /var/www/html/config/



COPY /.docker/telldus/ports.conf /etc/apache2/ports.conf
COPY /.docker/telldus/vhost.conf /etc/apache2/sites-available/000-default.conf