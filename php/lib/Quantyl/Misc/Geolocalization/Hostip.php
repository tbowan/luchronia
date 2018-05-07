<?php

namespace Quantyl\Misc\Geolocalization ;

/**
 * API from http://www.hostip.info/
 */

class Hostip implements Localizer {
    
    /*
     * Return an associative array :
     *   - country_name
     *   - country_code
     */
    public function localize($ip) {
        $json_content = file_get_contents("http://api.hostip.info/get_json.php?ip=$ip&position=true") ;
        $res = json_decode($json_content, true) ;
        return array(
            "country_name" => $res["country_name"],
            "country_code" => $res["country_code"]
                ) ;
    }
    
}
