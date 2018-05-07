<?php

namespace Services\User\Quanta ;

class Item extends \Services\Base\Character {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $user = $this->getUser() ;
        
        $form->addMessage(\I18n::QUANTA_BUY_ITEM_MESSAGE($user->quanta)) ;
        $form->addInput("character", new \Form\MyCharacter($user, \I18n::CHARACTER(), true)) ;
        $form->addInput("item", new \Form\BuyableItem($this->getUser()->quanta, \I18n::ITEM(), true)) ;
        $form->addInput("amount", new \Quantyl\Form\Fields\Integer(\I18n::AMOUNT(), true)) ;
    }

    public function onProceed($data) {
        
        $item = $data["item"] ;
        $user = $this->getUser() ;
        
        // Check quantas
        $cost = $data["amount"] * $item->price ;
        
        if ($user->quanta < $cost) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_QUANTA($cost, $user->quanta)) ;
        }
        
        $user->quanta -= $cost ;
        $user->update() ;
        
        \Model\Game\Ressource\Inventory::createFromValues(array(
            "item" => $item,
            "character" => $data["character"],
            "slot" => null,
            "amount" => $data["amount"]
        )) ;
    }
}
