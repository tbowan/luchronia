<?php

namespace Services\Game\Ministry\Homeland\Citizenship ;

class Change extends \Services\Base\Minister {
    
    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Homeland") ;
    }
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::HOMELAND_CITIZENSHIP_CHANGE_MESSAGE($this->city->getName())) ;
        $form->addInput("citizenship", new \Quantyl\Form\Model\EnumSimple(\Model\Enums\Citizenship::getBddTable(), \I18n::CITIZENSHIP_MODE()))
             ->setValue($this->city->citizenship);
    }
    
    public function onProceed($data) {
        $citizenship = $data["citizenship"] ;
        $this->city->citizenship = $citizenship ;
        $this->city->update() ;
        
        $this->setAnswer(new \Quantyl\Answer\Message(\I18n::HOMELAND_CITIZENSHIP_CHANGE_OK($this->city->getName(), $citizenship->getName()))) ;
    }


}
