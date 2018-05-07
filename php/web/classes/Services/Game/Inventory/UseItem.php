<?php

namespace Services\Game\Inventory ;

class UseItem extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Inventory::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Own inventory
        if (! $this->id->character->equals($this->getCharacter())) {
           throw new \Quantyl\Exception\Http\ClientForbidden() ; 
        }
        
        // can use
        if (! $this->id->isUsable()) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
    }
    
    public function getView() {
        
        $inv = $this->id ;
        
        $parch = \Model\Game\Ressource\Parchment::GetByItem($inv->item) ;
        $book  = \Model\Game\Ressource\Book::GetByItem($inv->item) ;
        
        
        if ($inv->isEatable() || $inv->isDrinkable()) {
            return new \Quantyl\Answer\Redirect("/Game/Inventory/Feed") ;
        } else if ($parch !== null) {
            return new \Quantyl\Answer\Redirect("/Game/Inventory/Learn/Parchment?inventory={$inv->id}") ;
        } else if ($book !== null) {
            return new \Quantyl\Answer\Redirect("/Game/Inventory/Learn/Book?inventory={$inv->id}") ;
        } else if ($inv->isModifier()) {
            return new \Quantyl\Answer\Redirect("/Game/Inventory/Modifier?id={$inv->id}") ;
        }
        
    }

}