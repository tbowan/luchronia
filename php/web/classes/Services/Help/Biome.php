<?php

namespace Services\Help ;

class Biome extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\NameOrId(\Model\Game\Biome::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        if ($this->id == null) {
            return new \Answer\View\Help\AllBiome($this);
        } else {
            return new \Answer\View\Help\Biome($this, $this->id);
        }
    }
    
    public function getTitle() {
        if ($this->id == null) {
            return parent::getTitle() ;
        } else {
            return \I18n::TITLE_HELP(\I18n::BIOME(), $this->id->getName()) ; 
        }
    }
    
}
