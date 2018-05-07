<?php

namespace Services\Game\Ministry\System\Senate ;

class Oppose extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("senator", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Senator::getBddTable())) ;
    }
    
    private $_source ;
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        
        $system = $this->senator->senate->system ;
        if (! $system->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        $this->_source = \Model\Game\Politic\Senator::GetActiveFromCharacter($this->senator->senate, $this->getCharacter()) ;
        
    }
    
    public function getView() {
        
        \Model\Game\Politic\Friend::Support($this->senator, $this->_source, -1) ;
        
        \Model\Game\Politic\Senator::GetOut($this->senator->senate) ;
        \Model\Game\Politic\Senator::GetIn($this->senator->senate) ;

    }
    
}
