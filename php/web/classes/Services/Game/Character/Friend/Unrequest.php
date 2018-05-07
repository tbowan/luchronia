<?php

namespace Services\Game\Character\Friend ;

use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Unrequest extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new Id(\Model\Game\Social\Request::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = $this->getCharacter() ;
        
        if (! $this->id->a->equals($char)) {
            // not own request
            // TOPO : better exception
            throw new \Exception() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_REQUEST_CONFIRM($this->id->b->getName())) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }

}
