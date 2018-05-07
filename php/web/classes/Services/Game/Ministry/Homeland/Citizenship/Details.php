<?php

namespace Services\Game\Ministry\Homeland\Citizenship ;

class Details extends \Services\Base\Character {
    
    private $_isMinister = null ;
    private $_myself = null ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("citizenship", new \Quantyl\Form\Model\Id(\Model\Game\Citizenship::getBddTable())) ;
    }

    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
         if (! $this->isMyself() && ! $this->isMinister()) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function isMinister() {
        if ($this->_isMinister === null) {
            $this->_isMinister = \Model\Game\Politic\Minister::hasPower($this->getCharacter(), $this->citizenship->city, \Model\Game\Politic\Ministry::GetByName("Homeland")) ;
        }
        return $this->_isMinister ;
    }
    
    public function isMyself() {
        if ($this->_myself === null) {
            $this->_myself = $this->citizenship->character->equals($this->getCharacter()) ;
        }
        return $this->_myself ;
    }
    
    public function getView() {
        
        return new \Answer\View\Game\Ministry\Homeland\Details($this, $this->citizenship, $this->isMyself(), $this->isMinister()) ;
    }

}
