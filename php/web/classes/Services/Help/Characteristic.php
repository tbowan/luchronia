<?php

namespace Services\Help ;

class Characteristic extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Characteristic::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if ($this->id == null) {
            return new \Answer\View\Help\Characteristic\ShowAll($this, $char) ;
        } else {
            return new \Answer\View\Help\Characteristic\ShowOne($this, $this->id, $char);
        }
    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
        return \I18n::TITLE_HELP(\I18n::CHARACTERISTIC(), $this->id->getName()) ; 
        }
    }
    
}
