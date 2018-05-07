<?php

namespace Quantyl\Misc\Geolocalization ;

interface Localizer {
    
    /*
     * Return an associative array :
     *   - country_name
     *   - country_code
     */
    public function localize($ip) ;
    
}
