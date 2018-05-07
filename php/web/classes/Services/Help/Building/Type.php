<?php

namespace Services\Help\Building ;

class Type extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Building\Type::getBddTable())) ;
    }
    
    public function getView() {
        if ($this->id === null) {
            return new \Answer\Widget\Help\Building\Type\ShowAll() ;
        } else {
            return new \Answer\Widget\Help\Building\Type\ShowOne($this->id);
        }
    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
            return \I18n::TITLE_HELP(\I18n::BUILDING_TYPE(), $this->id->getName()) ; 
        }
    }
    
}
