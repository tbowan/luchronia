<?php

namespace Services\Game\Ministry\Commerce ;

class Give extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Need same place
        if (! $this->city->equals($this->getCharacter()->position)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        }

    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("inventory", new \Form\Inventory($this->getCharacter(), \I18n::CHOSE_INVENTORY())) ;
    }
    
    public function onProceed($data) {
        $city = $this->getCharacter()->position ;
        $item = $data["inventory"]->item ;
        $amount = $data["inventory"]->amount ;

        \Model\Game\Ressource\City::GiveToCity($city, $item, $amount, $this->getCharacter()) ;
        
        $data["inventory"]->delete() ;
        
    }

    public function getCity() {
        return $this->stock->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Commerce") ;
    }

}
