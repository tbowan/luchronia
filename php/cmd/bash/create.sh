#!/bin/sh


# Parametres :
# $1 - taille du monde
# $2 - nom du monde
# $3 - répertoire des données

cd ..

in_dir="$3/Nasa/buining.com/Moon"


#
# STEP 1 : create cities
#
echo "--[ 1 - City Generation"
php -f exec.php -- -service World/Create -size $1 -name $2


read -p "Enter World Id : " id

#
# STEP 2 : linking cities
#

echo "--[ 2 - City linking"
php -f exec.php -- -service World/Neighbour -world $id

#
# STEP 3 : get DATA from Images
#

echo "--[ 3 - Set DATA"

echo "    3.1 Set Altitude and Albedo"
albedo="${in_dir}/MoonTM5760.png"
altitude="${in_dir}/MoonDEM0720.png"
echo "        Using following files (albedo and altitude)" 
echo "        - $albedo"
echo "        - $altitude"
php -f exec.php -- -service World/SetData -albedo $albedo -altitude $altitude -force 1 -world $id -d 1

echo "    3.1 Set biomes"
php -f exec.php -- -service World/SetBiome -world $id

