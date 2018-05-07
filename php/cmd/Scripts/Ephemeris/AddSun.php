<?php

/*
 * 
 * Ajout des éphémérides depuis le site web : http://ssd.jpl.nasa.gov/horizons.cgi?s_time=1#top
 * 
 * Les paramètres sont les suivants :
 *  - ephemeris type : observer
 *  - Target body : Sun
 *  - Observer location : Moon (body center) - il faut taper "@moon"
 *  - Time span : au choix
 *  - table settings : quantities=14, angleformat=deg
 *  - Display : default
 * 
 */

namespace Scripts\Ephemeris ;

class AddSun extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("file", new \Quantyl\Form\Fields\Text()) ;
    }

    public function getView() {
        
        echo "Adding Ephemeris\n" ;
        echo " - File : {$this->file}\n" ;
        
        $content = file_get_contents($this->file) ;
        foreach (explode("\n", $content) as $line) {
            $parts = preg_split("/ +/", $line) ;
            if (count($parts) == 5) {
                $time = strtotime($parts[1] . " " . $parts[2] . " GMT") ;

                $pos = \Quantyl\Misc\Vertex3D::FromSpheric($parts[3] + 180, 0.0 + $parts[4], 1.0, true) ;

                \Model\Game\Ephemeris\Sun::createFromValues(array(
                        "time" => $time + DT,
                        "x" => $pos->x(),
                        "y" => $pos->y(), 
                        "z" => $pos->z() 
                        ) ) ;

                echo \I18n::_date_time($time) . " " . $pos . "\n" ;
            }
        }
        echo " - done\n" ;
    }
    
}
