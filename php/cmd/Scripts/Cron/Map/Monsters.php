<?php

namespace Scripts\Cron\Map ;

class Monsters extends Base {
    
    public function getRgbByCity($city) {
        $c = 255 * ($city->monster_out / 2550) ;
        return array($c, $c, $c) ;
    }

}
