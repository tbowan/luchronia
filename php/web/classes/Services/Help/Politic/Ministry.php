<?php

namespace Services\Help\Politic ;

class Ministry extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Politic\Ministry::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        if ($this->id == null) {
            return new \Answer\View\Help\Politic\Ministry\ShowAll($this);
        } else {
            return new \Answer\View\Help\Politic\Ministry\ShowOne($this, $this->id);
        }
    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
            return \I18n::TITLE_HELP(\I18n::MINISTRY(), $this->id->getName()) ; 
        }
    }
    
}
