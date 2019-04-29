#!/bin/sh
rm -rf output
php bin/generate.php
rsync -avzuh -e ssh output/ ${HOST}:/var/www/html/web/www.eduvpn.org --progress --exclude '.git'
