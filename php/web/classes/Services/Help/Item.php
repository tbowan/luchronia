<?php

namespace Services\Help ;

class Item extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Ressource\Item::getBddTable())) ;
    }
    
    public function getView() {
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        return new \Answer\View\Help\Item($this, $this->id, $char);
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP(\I18n::ITEM(), $this->id->getName()) ; 
    }
}
