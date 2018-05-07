<?php

namespace Services\Game\Skill\Outdoor ;

class ProspectGround extends Base {
    
    public function getTime($tool) {
        return round(parent::getTime($tool) / $this->cs->level) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        $form->addInput("item", new \Form\ProspectionGround($this->getCharacter(), \I18n::RESSOURCE())) ;
    }
    
    public function doStuff($amount, $data) {
        
        $char = $this->getCharacter() ;
        $item = $data["item"] ;
        $nat  = \Model\Game\Ressource\Natural::GetFromCityAndItem($char->position, $item) ;
        $coef = $nat->coef ;
        
        \Model\Game\Ressource\Prospection::createFromValues(array(
            "character" => $char,
            "city" => $char->position,
            "item" => $item,
            "when" => time(),
            "coef" => $coef
        )) ;
        
        $msg = "" ;
        $msg .= \I18n::PROSPECTION_DONE($item->getName(), $coef) ;
        $msg .= parent::doStuff($amount, $data) ;
        
        return $msg ;
    }

    public function getToolInput() {
        return new \Form\Tool\Prospection($this->cs, $this->getComplete()) ;
    }

}
