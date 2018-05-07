<?php

namespace Services\Game\Character ;

class Background extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("c", new \Quantyl\Form\Model\Id(\Model\Game\Character::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->c->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("background", new \Quantyl\Form\Fields\FilteredHtml(\I18n::BACKGROUND()))
             ->setValue($this->c->background);
    }
    
    public function onProceed($data, $form) {
        $this->c->background = $data["background"] ;
        $this->c->update() ;
    }
    
}
