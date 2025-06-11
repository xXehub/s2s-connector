#!/bin/bash
echo '🚀 Starting SIMPLE queue worker...'
php artisan queue:work rabbitmq --queue=product-stock-update --verbose --tries=3 --timeout=60
