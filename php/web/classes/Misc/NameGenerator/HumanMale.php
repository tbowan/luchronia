<?php

namespace Misc\NameGenerator ;

class HumanMale implements Generator {
    
    public function generate() {
        $filename_first_names = BASE_DATA . DIRECTORY_SEPARATOR . "Names" . DIRECTORY_SEPARATOR . "FR-human_male_firstnames.txt" ;
		$filename_last_names  = BASE_DATA . DIRECTORY_SEPARATOR . "Names" . DIRECTORY_SEPARATOR . "FR-human_lastnames.txt" ;
		$list = file_get_contents($filename_first_names);
		$list2 = file_get_contents($filename_last_names); 
		$lines_fn   = explode("\n", $list);
		$lines_ln   = explode("\n", $list2);
		$first_name = $lines_fn[array_rand($lines_fn)];
		$last_name  = $lines_ln[array_rand($lines_ln)];
        
		return $first_name." ".$last_name ;
    }

    public static function Factory() {
        return new HumanMale() ;
    }

}
