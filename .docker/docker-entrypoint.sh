#!/usr/bin/env bash
set -e
role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    # composer install --no-interaction --prefer-dist --optimize-autoloader
    # php /var/www/html/artisan key:generate --force --no-interaction
    # php /var/www/html/artisan optimize
    # php /var/www/html/artisan migrate --force --no-interaction
    # php /var/www/html/artisan role:seed
    exec php-fpm
# elif [ "$role" = "queue" ]; then
#     echo "Running the queue..."
#     exec php /var/www/html/artisan horizon
# elif [ "$role" = "mqtt_uplink" ]; then
#     echo "Listening MQTT Uplink..."
#     exec php /var/www/html/artisan mqtt:subscribe
# elif [ "$role" = "redis_lwt" ]; then
#     echo "Listening Redis LWT..."
#     exec php /var/www/html/artisan redis:lwt
# elif [ "$role" = "scheduler" ]; then
#     echo "Starting scheduler..."
#     exec /usr/bin/supervisord -n -c /etc/supervisord.conf
else
    echo "Could not match the container role \"$role\""
    exit 1
fi