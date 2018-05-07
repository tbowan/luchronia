<?php

namespace Services\Game\Ministry\Government ;

class AddMinister extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("government", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Government::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->government->canManage($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("ministry", new \Quantyl\Form\Model\Checkbox(\Model\Game\Politic\Ministry::getBddTable(), \I18n::MINISTRY(), true)) ;
        $form->addInput("minister", new \Form\Citizen($this->government->system->city, \I18n::MINISTER(), true)) ;
    }
    
    public function onProceed($data) {
        
        foreach ($data["ministry"] as $ministry) {
            $m = \Model\Game\Politic\Minister::addMinister($data["minister"], $this->government, $ministry);
        }

    }
    
}
