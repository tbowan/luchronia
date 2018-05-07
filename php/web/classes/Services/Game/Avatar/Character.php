<?php

namespace Services\Game\Avatar ;

class Character extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("character", new \Quantyl\Form\Model\Id(\Model\Game\Character::getBddTable(), "", false)) ;
        $form->addInput("type", new \Quantyl\Form\Fields\Text()) ;
        
    }
    
    public function getView() {
        
        switch ($this->type) {
            case "mini" : return new \Answer\Widget\Avatar\Mini($this->character) ;
            case "med"  : return new \Answer\Widget\Avatar\Med($this->character) ;
            default     : return new \Answer\Widget\Avatar\Full($this->character) ;
        }
    }
    
    
}
