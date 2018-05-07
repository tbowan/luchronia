<?php

namespace Misc\NameGenerator ;

class SeleniteFemale implements Generator {
    
    public function generate() {
		$filename = BASE_DATA . DIRECTORY_SEPARATOR . "Names" . DIRECTORY_SEPARATOR . "esperanto.txt" ;
		$list  = file_get_contents($filename);
		$lines   = explode("\n", $list);			
				
		$nb_syl1 = rand(0,3);
		$nb_syl2 = rand(1,3);				
		$first_name="";
		$last_name = "";
						
		for($i=0; $i<$nb_syl1; $i++){
			$first_name .=  trim($lines[array_rand($lines)]);
		}

		for($i=0; $i<$nb_syl2; $i++){
			$last_name .=   trim($lines[array_rand($lines)]);					
		}
		
		foreach($lines as $num=>$syllabe){
			if(substr_compare(trim($syllabe), "a", -1, 1) !=0){
				unset($lines[$num]);
			}
		}

		$first_name .=  trim($lines[array_rand($lines)]);
		$last_name .=   trim($lines[array_rand($lines)]);		
		
		$first_name = ucfirst($first_name);				
		$last_name  = ucfirst($last_name);					

        return $first_name." ".$last_name ;
    }

    public static function Factory() {
        return new SeleniteFemale() ;
    }

}
