<?php

namespace Services\Game\Ministry\Homeland\Citizenship ;

use Model\Game\City;
use Model\Game\Politic\Ministry;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Invite extends \Services\Base\Minister {

    public function getCity() {
        return $this->city ;
    }

    public function getMinistry() {
        return Ministry::GetByName("Homeland") ;
    }
    
    public function fillParamForm(Form &$form) {
        $form->addInput("city", new Id(City::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::CITIZENSHIP_INVITE_MESSAGE()) ;
        $form->addInput("character", new \Form\Character()) ;
        $form->addInput("message", new FilteredHtml(\I18n::MESSAGE())) ;
    }

    public function onProceed($data) {
        
        $target = $data["character"] ;
        
        if (\Model\Game\Citizenship::HasPending($target, $this->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::CITIZENSHIP_HASPENDING()) ;
        } else {
            
            $time = time() ;
        
            $citizenship = \Model\Game\Citizenship::createFromValues(array(
                    "character" => $target,
                    "city"      => $this->city,
                    "created"   => $time,
                    "from"      => null,
                    "isInvite"  => true
                    )) ;

            // add message
            \Model\Game\Citizenship\Message::createFromValues(array(
                    "citizenship" => $citizenship,
                    "character"   => $this->getCharacter(),
                    "message"     => $data["message"],
                    "date"        => $time
            )) ;
            
            $this->setAnswer(new \Quantyl\Answer\Message(\I18n::CITIZENSHIP_INVITE_DONE($target->id, $target->getName()))) ;
        }
        
        
    }

}
