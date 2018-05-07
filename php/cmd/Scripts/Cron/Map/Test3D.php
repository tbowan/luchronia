<?php

namespace Scripts\Cron\Map ;

class Test3D extends Base {
    
    
    public function getRgbByCity($city) {
        
        return array(
                floor(255 * abs($city->x)),
                floor(255 * abs($city->y)),
                floor(255 * abs($city->z))
                ) ;
    }


}
