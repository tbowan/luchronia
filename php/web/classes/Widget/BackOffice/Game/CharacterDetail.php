<?php

namespace Widget\BackOffice\Game ;

class CharacterDetail extends \Quantyl\Answer\Widget {
    
    private $_character ;
    
    public function __construct(\Model\Game\Character $char) {
        $this->_character = $char ;
    }
    
    public function getIdentity($char) {
        $res = "" ;
        
        $res .= $this->_character->getImage("full") ;
        
        $res .= "<h2>" . \I18n::INFORMATIONS() . "</h2>" ;
        $res .= "<ul>" ;
        $res .= "<li><strong>" .\I18n::NAME(). " :</strong> " . $char->getName() . "</li>" ;
        $res .= "<li><strong>" .\I18n::SEX(). " :</strong> " . $char->sex->getName() . "</li>" ;
        $res .= "<li><strong>" .\I18n::RACE(). " :</strong> " . $char->race->getName() . "</li>" ;
        $res .= "<li><strong>" .\I18n::POSITION(). " :</strong> "
                . new \Quantyl\XML\Html\A("/BackOffice/Game/City?id={$char->position->id}", $char->position->getName())
                . "</li>" ;
        $res .= "<li><strong>" .\I18n::USER(). " :</strong> "
                . new \Quantyl\XML\Html\A("/BackOffice/User/Show?id={$char->user->id}", $char->user->getName())
                . "</li>" ;
        
        // TODO : Other fields
                
        $res .= "</ul>" ;
        return $res ;
    }
    
    public function getMaintenance($char) {
        $res = "<h2>" . \I18n::CHARACTER_MAINTENANCE() . "</h2>" ;
        
        $res .= "<ul>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/BackOffice/Game/Control?character={$char->id}", \I18n::CHARACTER_TAKE_CONTROL()) . "</li>" ;
        $res .= "</ul>" ;
        return $res ;
    }
    
    public function getSkills($char) {
        $res = "" ;
        $res .= "<h2>" . \I18n::SKILLS() . "</h2>" ;
        // TODO : display skills
        return $res ;
    }
    
    public function getObjects($char) {
        $res = "" ;
        $res .= "<h2>" . \I18n::INVENTORY() . "</h2>" ;
        // TODO : display objects
        return $res ;
    }
    
    
    public function getContent() {
        
        $res = "" ;
        
        $res .= $this->getIdentity($this->_character) ;
        $res .= $this->getMaintenance($this->_character) ;
        $res .= $this->getSkills($this->_character) ;
        $res .= $this->getObjects($this->_character) ;
        
        return $res ;
        
    }
    
}
