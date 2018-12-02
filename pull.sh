#!/usr/bin/env sh

GREEN='\033[1;32m'
NC='\033[0m'
echo "${GREEN}$ git pull ${NC}"
git pull
echo "${GREEN}$ php7.2 composer.phar update ${NC}"
php7.2 composer.phar update