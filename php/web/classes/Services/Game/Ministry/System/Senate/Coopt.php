<?php

namespace Services\Game\Ministry\System\Senate ;

class Coopt extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("system", new \Quantyl\Form\Model\Id(\Model\Game\Politic\System::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->system->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("citizens", new \Form\Character\Citizen($this->system->city, \I18n::CITIZEN())) ;
    }
    
    public function onProceed($data) {
        $senate = \Model\Game\Politic\Senate::GetFromSystem($this->system) ;
        $senator = \Model\Game\Politic\Senator::GetActiveFromCharacter($senate, $this->getCharacter()) ;
        
        foreach ($data["citizens"] as $citizen) {
            \Model\Game\Politic\Friend::coopt($senator, $citizen) ;
        }
                
        // \Model\Game\Politic\Senator::GetOut($senate) ;
        \Model\Game\Politic\Senator::GetIn($senate) ;
    }
    
}
