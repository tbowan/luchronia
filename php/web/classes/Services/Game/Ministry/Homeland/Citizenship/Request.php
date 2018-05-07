<?php

namespace Services\Game\Ministry\Homeland\Citizenship ;

class Request extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Check
        $closed = \Model\Enums\Citizenship::CLOSED() ;
        if ($this->city->citizenship->equals($closed)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        if ( ! $this->city->hasTownHall()) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
        $pending = \Model\Game\Citizenship::GetPending($this->getCharacter(), $this->city) ;
        if ($pending != null && $pending->from != null) {
            // Already citizen
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_ALREADY_CITIZEN()) ;
        } else if ($pending != null && $pending->from == null) {
            // A request exists
            // TODO #710
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $ondemand = \Model\Enums\Citizenship::ON_DEMAND() ;
        if ($this->city->citizenship->equals($ondemand)) {
            $form->setMessage(\I18n::CITIZENSHIP_REQUEST_ONDEMAND_MSG()) ;
            $form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
        } else {
            $form->setMessage(\I18n::CITIZENSHIP_REQUEST_OPEN_MSG()) ;
            //$form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE())) ;
        }
    }
    
    public function proceed_open() {
        $time = time() ;
        
        \Model\Game\Citizenship::createFromValues(array(
                "character" => $this->getCharacter(),
                "city"      => $this->city,
                "created"   => $time,
                "from"      => $time,
                "isInvite"  => false
                )) ;
        
        return \I18n::CITIZENSHIP_REQUEST_WASOPEN($this->city->getName()) ;
    }
    
    public function proceed_demand($message) {
        $char = $this->getCharacter() ;
        $time = time() ;
        
        $citizenship = \Model\Game\Citizenship::createFromValues(array(
                "character" => $char,
                "city"      => $this->city,
                "created"   => $time,
                "from"      => null,
                "isInvite"  => false
                )) ;
        
        // add message
        \Model\Game\Citizenship\Message::createFromValues(array(
                "citizenship" => $citizenship,
                "character"   => $char,
                "message" => $message,
                "date" => $time
        )) ;
        
        return \I18n::CITIZENSHIP_REQUEST_REQUEST_CREATED($this->city->getName(), $message) ;
    }
    
    public function onProceed($data) {
        $open = \Model\Enums\Citizenship::OPEN() ;
        if ($this->city->citizenship->equals($open)) {
            $msg = $this->proceed_open() ;
        } else {
            // On demand, create a request
            $msg = $this->proceed_demand($data["message"]) ;
        }
        $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
    }
    
}
