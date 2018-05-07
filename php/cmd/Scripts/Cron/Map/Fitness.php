<?php

namespace Scripts\Cron\Map ;

class Fitness extends Base {
    
    public function getRgbByCity($city) {
        $c = 255 * (log(1 -  $city->fitness) / log(1000)) ;
        return array($c, $c, $c) ;
    }

}
