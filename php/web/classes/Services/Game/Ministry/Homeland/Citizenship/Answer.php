<?php

namespace Services\Game\Ministry\Homeland\Citizenship ;

class Answer extends \Services\Base\Character {
    
    private $_isMinister = null ;
    private $_myself = null ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("citizenship", new \Quantyl\Form\Model\Id(\Model\Game\Citizenship::getBddTable())) ;
    }

    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->citizenship->from !==  null) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if ($this->citizenship->isInvite && ! $this->isMyself()) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        } else if (! $this->citizenship->isInvite && ! $this->isMinister()) {
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
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        // DONT CHANGE THE KEY, used in the switch in on proceed
        $choices = array (
            0 => \I18n::CITIZENSHIP_POSTPONE(),
            1 => \I18n::CITIZENSHIP_ACCEPT(),
            2 => \I18n::CITIZENSHIP_REFUSE()
        ) ;
        
        $form->addInput("choice", new \Quantyl\Form\Fields\Radio(\I18n::ANSWER(), true))->setChoices($choices) ;
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
        
        // Order of case made simpler, cf. fillDataForm
        switch ($data["choice"]) {
            case 2 : // refuse
                $this->citizenship->to = $now ;
            case 1 : // accept
                $this->citizenship->from = $now ;
            default : // postpone
                $this->citizenship->update() ;
        }
        
    }
    
    
}
