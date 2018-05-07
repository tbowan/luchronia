<?php

namespace Services\Game\Character ;

class Repatriate extends \Services\Base\Character {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::REPATRIATE_MESSAGE()) ;
        $form->addInput("city", new \Form\CityRepatriate($this->getCharacter())) ;
        
    }
    
    public function onProceed($data) {
        
        $char = $this->getCharacter() ;
        $target = $data["city"] ;
        
        $kmc            = (2.0 * pi() * 1736.0) / (5 * $char->position->world->size) ;
        $dist_km        = \Model\Game\City::GetDist($char->position, $target) ;
        $dist_city      = $dist_km / $kmc ;
        $time_cost      = round(10000 * $dist_city, 2) ;
        
        if ($char->isCitizen($target)) {
            $credit_cost = 0 ;
        } else {
            $credit_cost = round($target->repatriate_cost * $dist_km, 2);
        }
        
        // Check enough credits
        if ($char->getCredits() < $credit_cost) {
            throw new \Exception(\I18n::EXP_REPATRIATE_NOT_ENOUGH_CREDITS()) ;
        }
        
        $target->addCredits($credit_cost) ;
        
        $char->addCredits(- $credit_cost) ;
        $char->position = $target ;
        $char->addTime( - $time_cost) ;
        $char->update() ;
    }
    
}
