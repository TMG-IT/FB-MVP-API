#!/bin/bash

# Dependencies install
composer install

# Database setup
php bin/console do:sc:dr --force
php bin/console do:sc:up --force

# Fixtures load
php bin/console ha:fi:lo -n

# Cache clear
php bin/console ca:cl --no-warmup