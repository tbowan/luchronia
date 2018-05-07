<?php

namespace Services\Game\Ministry\System ;

class UnSupport extends \Services\Base\Character {

    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("revolution", new \Quantyl\Form\Model\Id(\Model\Game\Politic\Revolution::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::REVOLUTION_UNSUPPORT_MESSAGE()) ;
    }
    
    public function onProceed($data) {
        \Model\Game\Politic\Support::doUnSupport($this->revolution, $this->getCharacter()) ;
    }

}
