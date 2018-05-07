<?php

namespace Services\Game\Skill\Outdoor ;

class Move extends Base {

    public function fillParamForm(\Quantyl\Form\Form &$form) {
        parent::fillParamForm($form) ;
        $form->addInput("target", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    private $_n ;
    private $_c ;
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        $this->_n = \Model\Game\City\Neighbour::getFromAB($this->target, $me->position) ;
        
        if ($this->_n === null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CITIES_ARENT_CONNETED()) ;
        } else {
            $this->_c = $this->_n->getPathCost() ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        $form->addMessage(\I18n::MOVE_FORM_MESSAGE($this->target->id, $this->target->getName())) ;
    }
    
    public function getToolInput() {
        return new \Form\Tool\Move($this->cs, $this->getComplete(), $this->_c) ;
    }
    
    public function getTime($tool) {
        return round($this->_c * parent::getTime($tool) / $this->cs->level) ;
    }
    
    public function getComplete() {
        return new \Model\Game\Tax\Complete(0, 0) ;
    }
    
    public function doStuff($amount, $data) {
        
        $char = $this->getCharacter() ;
        $char->position = $this->target ;
        $char->update() ;
        
        \Model\Stats\Game\Moves::Visit($char, $this->target) ;
        
        $msg .= parent::doStuff($amount, $data);
        return $msg ;
    }
    
}
