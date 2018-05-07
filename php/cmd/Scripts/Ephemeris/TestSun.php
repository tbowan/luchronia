<?php

namespace Scripts\Ephemeris ;

class TestSun extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("time", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function getView() {
        
        echo "Testing Ephemeris\n" ;
        
        $time = strtotime($this->time) ;
        $pos = \Model\Game\Ephemeris\Sun::GetPosByTime($time) ;

    }
    
}
