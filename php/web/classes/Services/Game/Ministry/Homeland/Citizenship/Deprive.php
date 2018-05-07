<?php

namespace Services\Game\Ministry\Homeland\Citizenship ;

class Deprive extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("citizenship", new \Quantyl\Form\Model\Id(\Model\Game\Citizenship::getBddTable())) ;
    }

    public function getCity() {
        return $this->citizenship->city ;
    }

    public function getMinistry() {
        return \Model\Game\Politic\Ministry::GetByName("Homeland") ;
    }

    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::CITIZENSHIP_DEPRIVE_MESSAGE()) ;
        $form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
    }
    
    public function onProceed($data, $form) {
        $now = time() ;
        
        $message = \Model\Game\Citizenship\Message::createFromValues(array(
            "citizenship" => $this->citizenship,
            "character"   => $this->getCharacter(),
            "date"        => $now,
            "message"     => $data["message"]
        )) ;
        
        $this->citizenship->to = $now ;
        $this->citizenship->update() ;
        
        
    }
    
}
