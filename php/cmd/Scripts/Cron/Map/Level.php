<?php

namespace Scripts\Cron\Map ;

class Level extends Base {
    
    public function getBuildingCount($v) {
        return floor(17 * $v / 229) ;
    }
    
    public function getTownHallLevel($v) {
        $levels = array(
            -1 => 0,
            0 => 1,
            1 => 2,
            2 => 4,
            3 => 7,
            4 => 11
        ) ;
        
        foreach ($levels as $i => $t) {
            if ($v <= $t) {
                return $i ;
            }
        }
        return 5 ;
    }
    
    public function getRgbByCity($city) {
        
        $blue = 255 - $city->albedo ;
        $n = intval($city->name) ;
        $c = $this->getBuildingCount($n) ;
        $t = $this->getTownHallLevel($c) ;
        
        $colors = array(
            -1 => array(  0,   0,   0), // black
             0 => array(  0,   0, 255), // Blue
             1 => array(  0, 128, 128), // turquoise
             2 => array(  0, 255,   0), // green
             3 => array(255, 255,   0), // yellow
             4 => array(255, 128,   0), // orange
             5 => array(255,   0,   0)  // red
        ) ;
        
        
        return $colors[$t] ;
    }

}
