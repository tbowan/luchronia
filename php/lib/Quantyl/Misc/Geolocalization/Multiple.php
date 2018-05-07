<?php

namespace Quantyl\Misc\Geolocalization ;

class Multiple implements Localizer {
    
    private $_localizers ;
    
    public function __construct() {
        $this->_localizers = array() ;
    }
    
    public function addLocalizer(Localizer $l) {
        $this->_localizers[] = $l ;
    }
    
    public function localize($ip) {
        $scores = array() ;
        $res = array() ;
        $first = null ;
        
        foreach ($this->_localizers as $l) {
            $temp = $l->localize($ip) ;
            $code = $temp["country_code"] ;
            if ($first === null) {
                $first = $code ;
            }
            if (! isset($res[$code])) {
                $res[$code] = $temp ;
            }
            if (! isset($scores[$code])) {
                $scores[$code] = 0 ;
            }
            $scores[$code] ++ ;
        }
        
        $found = $this->getBest($scores, $first) ;
        return $res[$found] ;
    }
    
    public function getBest($scores, $first) {
        $best = null ;
        $mult = false ;
        $score = 0 ;
        foreach ($scores as $code => $s) {
            if ($s > $score) {
                $best = $code ;
                $score = $s ;
                $mult = false ;
            } else if ($s == $score) {
                $mult = true ;
            }
        }
        
        if ($mult) {
            return $first ;
        } else {
            return $best ;
        }
    }
    
}
