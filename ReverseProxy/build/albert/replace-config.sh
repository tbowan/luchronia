#!/bin/sh

echo "Remplacement du fichier de configuration"

cp $1 /etc/apache2/sites-available/luchronia.com

service apache2 reload

echo "Done"
