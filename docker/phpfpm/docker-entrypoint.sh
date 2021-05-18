#!/bin/bash
set -e

green='\033[0;32m'
purple='\033[0;35m'
nc='\033[0m' #no color

cd /var/www/html
#composer install

exec "$@"