<?php

namespace Scripts\Avatar ;

class ListToSQL extends \Quantyl\Service\EnhancedService {
    
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("directory", new \Quantyl\Form\Fields\Text()) ;
    }

    public function scanLayered($r, $s, \Model\Game\Avatar\Layer $l, $directory) {
        
        $basedir = str_replace("../../data/Files/icones/Model/Avatar//", "", $directory) ;
        
        foreach (scandir($directory) as $file) {
            if ($file != "." && $file != "..") {
                
                $parts = explode("-", $file) ;
                $price = $parts[0] ;
                
                $filename = $basedir . DIRECTORY_SEPARATOR . $file ;
                echo "("
                        . ($r == null ? "null" : $r->getId())
                        . ", "
                        . ($s == null ? "null" : $s->getId())
                        . ", "
                        . $l->getId()
                        . ", "
                        . "\"{$filename}\""
                        . ", "
                        . $price
                        . "),\n" ;
            }
        }
    }
    
    public function scanPriced($r, $s, $directory) {
        
        foreach (scandir($directory) as $file) {
            if ($file != "." && $file != "..") {
                $parts = explode("-", $file) ;
                $layerid = intval($parts[0]) ;
                $layer = \Model\Game\Avatar\Layer::GetById($layerid) ;
                
                $this->scanLayered($r, $s, $layer, $directory . DIRECTORY_SEPARATOR . $file) ;
            }
        }
        
    }
    
    public function scanRaceSex($r, $s, $directory) {
        foreach (scandir($directory) as $file) {
            switch($file) {
                case "Gratuits" :
                case "Payants" :
                    $this->scanPriced($r, $s, $directory. DIRECTORY_SEPARATOR . $file) ;
                    break ;
                default:
                    break ;
            }
        }
        
    }
    
    public function scanBase($dir) {
        
        foreach (scandir($dir) as $file) {
            if ($file == "." || $file == "..") {
                continue ;
            } else if ($file == "COMMON") {
                    $race = null ;
                    $sex = null ;
            } else {
                $parts = explode("-", $file) ;
                $racename = $parts[0] ;
                $sexname  = $parts[1] ;
                $race = \Model\Enums\Race::$racename() ;
                $sex = \Model\Enums\Sex::$sexname() ;
            }
            $this->scanRaceSex($race, $sex, $dir . DIRECTORY_SEPARATOR . $file) ;
        }
        
    }
    
    public function getView() {
        echo "INSERT INTO `game_avatar_item` (`race`, `sex`, `layer`, `filename`, `price`) VALUES\n" ;
        $this->scanBase($this->directory) ;
    }
    
    
}
