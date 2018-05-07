#!/bin/sh

Parcours(){
ls |while read i
do
    if [ -d "$i" ]; then
		echo "+$i"
		cd "$i"
		Parcours $i
		cd ..
	else if [ -f "$i" ] && [ "`mimetype -b -i \"$i\"`" = "image/x-apple-ios-png" ]; then
			
			res=`echo $i | grep "mini"`
			res2=`echo $i | grep "med"`
			if [ -z "$res" ] && [ -z "$res2" ]; then
				# if [ "$1" = "03-nez" ]; then
					# convert -crop 65x104+177+260 $i temp$i
					# convert temp$i -resize 50x80 radio-$i
					# rm temp$i
				# elif [ "$1" = "05-Bouche" ]; then
					convert -crop 100x160+159+296 $i temp$i
					convert temp$i -resize 50x80 radio-$i
					rm temp$i
				# elif [ "$1" = "07-Sourcils" ]; then
					# convert -crop 205x328+109+140 $i temp$i
					# convert temp$i -resize 50x80 radio-$i
					# rm temp$i						
				# elif [ "$1" = "09-Yeux" ]; then
					# convert -crop 190x304+115+169 $i temp$i
					# convert temp$i -resize 50x80 radio-$i
					# rm temp$i				
				# fi
				#echo  "paramètre "$1
				#convert $i -resize 100x160 med-$i
			fi

		fi
    fi
	
done
}

Parcours