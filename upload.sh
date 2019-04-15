#!/bin/sh
rm -rf output/*
php bin/generate.php
rsync -avzuh -e ssh output/ helium.tuxed.net:/var/www/html/fkooman/eduvpn.org --progress --exclude '.git'
