#!/bin/sh

for i in Metiers/*.png
do
	base=`basename $i .png`
    composite -compose Over -geometry 256x256 $i Seal/seal.png Done/$base.png
	#for j in "Wood" "Bronze" "Silver" "Gold" "Platinium" "Cristal"
	#do
	#	composite -compose Over -geometry 256x256 $i Seal/${j}_seal.png Done/${base}_${j}.png
	#done
done
