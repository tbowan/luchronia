<?php

namespace Answer\Widget\Game ;

class Screenshots extends \Answer\Widget\Misc\Section {
    
    private $_root ;
    
    public function __construct($root = "", $class = "", $more = null) {
        $this->_root = $root ;
        parent::__construct(
                \I18n::SCREENSHOTS(),
                $more,
                "",
                null,
                "$class") ;
    }
    
    private function translate($filename) {
        $matches = array() ;
        $found = preg_match("/(?:[0-9]*-)?([^\.]*)(?:\.[a-z]+)?/", $filename, $matches) ;
        if ($found) {
            $key = $matches[1] ;
        } else {
            $key = $filename ;
        }
        
        return \I18n::translate($key) ;
        
    }
    
    
    private function add_img($url_full, $url_thumb, $name) {
        $title = $this->translate($name) ;
        $res  = "<div class=\"thumb\">" ;
        $res .= "<a href=\"{$url_full}\">" ;
        $res .= "<img"
                . " src=\"{$url_thumb}\""
                . " alt=\"" . urlencode($title) . "\""
                . " />" ;
        $res .= "</a>" ;
        $res .= "</div>" ;
        return $res ;
    }
    
    private function add_dir($path_full, $path_thumb, $url_full, $url_thumb, $name, $depth) {
        
        // Title part
        if ($depth > 0 && $depth < 6) {
            $n = $depth + 1 ;
            $title = $this->translate($name) ;
            $res = "<h$n>" . $title . "</h$n>" ;
        } else {
            $res = "" ;
        }
        
        $images = "" ;
        $subs   = "" ;
        foreach (scandir($path_full) as $filename) {
            if (strpos($filename, ".") === 0) {
                continue ;
            }
            $file_full  = $path_full  . DIRECTORY_SEPARATOR . $filename ;
            $file_thumb = $path_thumb . DIRECTORY_SEPARATOR . $filename ;
            $file_url_f = $url_full . "/" . $filename ;
            $file_url_t = $url_thumb . "/" . $filename ;
            if (is_dir($file_full) && is_dir($file_thumb)) {
                $subs .= $this->add_dir($file_full, $file_thumb, $file_url_f, $file_url_t, $filename, $depth + 1) ;
            } else if (is_file($file_full) && is_file($file_thumb)) {
                $images .= $this->add_img($file_url_f, $file_url_t, $filename) ;
            }
        }
        
        $res .= $images ;
        $res .= $subs ;
        return $res ;
    }
    
    
    public function getBody() {
        
        $root = $this->_root ;
        
        $path_full  = BASE_DATA . DIRECTORY_SEPARATOR
                . "screenshots" . DIRECTORY_SEPARATOR
                . "Full" . DIRECTORY_SEPARATOR
                . $root ;
        $path_thumb = BASE_DATA . DIRECTORY_SEPARATOR
                . "screenshots" . DIRECTORY_SEPARATOR
                . "Thumb" . DIRECTORY_SEPARATOR
                . $root ;
        
        $url_full   = "/Media/screenshots/Full/$root" ;
        $url_thumb  = "/Media/screenshots/Thumb/$root" ;
        
        $res = "" ;
        $res .= $this->add_dir($path_full, $path_thumb, $url_full, $url_thumb, "", 0) ;
        
        return $res ;
        
    }
    
    
    
}
