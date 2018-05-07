#!/bin/sh
#Pour utiliser le script, copier le répertoire avec les dessins ici, puis modifier Clay avec le nom du materiaux
Materiau="Timbered"
for i in $Materiau/*.png
do
	basename=`basename $i .png`
	for j in 1 2 3 4 5
	do
	 composite -compose Over -geometry 256x256  Map.png  $i temp/$basename
	 composite -compose Over $j.png temp/$basename Done/Map$basename$Materiau$j.png
	done
done
