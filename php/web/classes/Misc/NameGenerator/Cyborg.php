<?php

namespace Misc\NameGenerator ;

abstract class Cyborg implements Generator {
    
    public function generate() {
		$filename_names = BASE_DATA . DIRECTORY_SEPARATOR . "Names" . DIRECTORY_SEPARATOR . "FR-cyborg_names.txt" ;
		$filename_adjectives = BASE_DATA . DIRECTORY_SEPARATOR . "Names" . DIRECTORY_SEPARATOR . "FR-cyborg_adjective.txt" ;
        
		$list1 = file_get_contents($filename_names);
		$list2 = file_get_contents($filename_adjectives);
		$lines1   = explode("\n", $list1);
		$lines2   = explode("\n", $list2);
		$name = $lines1[array_rand($lines1)];
		$adj  = $lines2[array_rand($lines2)];
			
		$id="";
		$alphabet= array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$id = $alphabet[array_rand($alphabet)]."-";
		$id .= strval(rand(0,999));
			
		return $name." ".$adj." ".$id ;
    }

}
