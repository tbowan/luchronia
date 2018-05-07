#!/bin/sh

liste="ParchmentProspectGround ParchmentProspectUnderground ParchmentProspectArcheo" 
for i in $liste
do
	#basename=`basename $i .png`
	basename=`echo $i | sed -e "s/Parchment//"`
    composite -compose Over -geometry 256x256 Skill/$basename.png Parchment.png Done/Parchment$basename.png
done
