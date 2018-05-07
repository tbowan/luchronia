<?php

namespace Services\Game\Ministry\Government ;

class Show extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("government", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Government::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $gov = $this->government ;
        if (! $gov->canSee($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function getView() {
        $gov = $this->government ;
        if ($gov->canManage($this->getCharacter())) {
            return new \Widget\Game\Ministry\Government\Manage($this->government) ;
        } else {
            return new \Widget\Game\Ministry\Government\Details($this->government) ;
        }
    }
    
}
