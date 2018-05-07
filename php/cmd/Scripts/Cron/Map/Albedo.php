<?php

namespace Scripts\Cron\Map ;

class Albedo extends Base {
    
    public function getRgbByCity($city) {
        $c = 255 - $city->albedo ;
        return array($c, $c, $c) ;
    }

}
