#!/bin/sh

for i in 1 2 3 4
do
	for line in `cat $i.txt`
	do
		basename=`echo $line | sed -e "s/Book//"`
		composite -compose Over -geometry 256x256 Skill/$basename.png $i.png Done/Book$basename.png
	done
done
 
