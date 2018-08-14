#!/bin/bash

source ./_config.sh
echo "starting web server..."
echo "PHP_BIN=$PHP_BIN"
export PATH="$PHP_BIN:$PATH"
#export PATH_OLD=$PATH
#echo "$PATH"
php -v
php artisan serve
