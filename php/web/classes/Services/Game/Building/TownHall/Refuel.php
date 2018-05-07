<?php

namespace Services\Game\Building\TownHall ;

class Refuel extends \Services\Base\Character {
    
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // CHere there is a townhall
        if (! $this->getCharacter()->position->hasTownHall()) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_REFUEL_WITHOUT_TOWNHALL()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::REFUEL_MESSAGE($this->getCharacter()->refueling, $this->getCharacter()->point)) ;
    }
    
    public function onProceed($data) {
        
        $char = $this->getCharacter() ;
        $char->refuel() ;
        
    }
}
