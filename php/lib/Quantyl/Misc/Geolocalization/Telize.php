<?php

namespace Quantyl\Misc\Geolocalization ;

/**
 * Api from http://www.telize.com/
 */

class Telize implements Localizer {
    
    /*
     * Return an associative array :
     *   - country_name
     *   - country_code
     */
    public function localize($ip) {
        $json_content = file_get_contents("http://www.telize.com/geoip/$ip") ;
        $res = json_decode($json_content, true) ;
        return array(
            "country_name" => strtoupper($res["country"]),
            "country_code" => $res["country_code"]
                ) ;
    }
    
}
