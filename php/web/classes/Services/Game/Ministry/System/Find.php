<?php

namespace Services\Game\Ministry\System ;

class Find extends \Services\Base\Character {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::FIND_REVOLUTION_MESSAGE()) ;
        $form->addInput("id", new \Quantyl\Form\Model\Name(\Model\Game\Politic\Revolution::getBddTable(), \I18n::SECRET_ID())) ;
    }
    
    public function onProceed($data, $form) {
        $system = $data["id"] ;
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Ministry/System?system={$system->id}")) ;
    }
    
}
