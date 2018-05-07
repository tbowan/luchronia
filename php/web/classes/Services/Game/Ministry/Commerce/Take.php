<?php

namespace Services\Game\Ministry\Commerce ;

class Take extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("stock", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\City::getBddTable())) ;
    }

    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // need outside stock
        if ($this->stock->instance !== null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CANNOT_TAKE_PROTECTED_STOCK()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::TAKE_MESSAGE($this->stock->amount, $this->stock->item->getName())) ;
    }
    
    public function onProceed() {
        $before = $this->stock->amount ;
        $remain = \Model\Game\Ressource\Inventory::AddItem($this->getCharacter(), $this->stock->item, $this->stock->amount) ;
        if ($before > $remain) {
            \Model\Game\City\Register::createFromValues(array(
                "character" => $this->getCharacter(),
                "city" => $this->stock->city,
                "from" => $this->stock->instance,
                "ressource" => $this->stock->item,
                "amount" => $remain - $before
            )) ;
        }
        $this->stock->amount = $remain ;
        $this->stock->update() ;
    }

}
