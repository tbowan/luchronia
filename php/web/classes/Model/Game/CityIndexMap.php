<?php

namespace Model\Game ;

class CityIndexMap {
    
    private $_width  ;
    private $_height ;
    private $_side   ;
    private $_cities ;
    
    private function __construct($width, $height, $cities, $column = true) {
        $this->_width  = $width ;
        $this->_height = $height ;
        $this->_cities = $cities ;
        $this->_side   = $column ;
    }
    
    public function getHeight() { return $this->_height ; }
    public function getWidth()  { return $this->_width ;  }
    
    /* Get Data */
    
    public function get($x, $y) {
        if ($this->_side) {
            $idx = $x * $this->_height + $y ;
        } else {
            $idx = $y * $this->_width + $x ;
        }
        if (! isset($this->_cities[$idx])) {
            return null ;
        } else {
            return $this->_cities[$idx] ;
        }
    }
    
     
    /* Input / Output */
    
    public static function CreateFromFile($file) {
        $content = unpack("I*", file_get_contents($file)) ;
        
        $width = array_shift($content) ;
        $height = array_shift($content) ;
        
        return new CityIndexMap($width, $height, $content) ;
    }
    
    public static function CreateFromQtFile($file) {
        
        $content = explode(" ", file_get_contents($file)) ;
        $width = array_shift($content) ;
        $height = array_shift($content) ;
        
        return new CityIndexMap($width, $height, $content, false) ;
    }
    
}
