<?php


namespace Answer\Widget\Game\Social ;

class CharacterAsItem extends \Quantyl\Answer\Widget {

    private $_char ;
    private $_name ;
    
    public function __construct(\Model\Game\Character $char, $name = "") {
        $this->_char = $char ;
        $this->_name = $name ;
    }
    
    public function getContent() {
        $character = $this->_char ;
        
        $res  = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $character->getImage("mini") . "</div>" ;
        $res .= "<div class=\"description\">"
                . "<div class=\"name\">" . $this->getName($character) . "</div>"
                . "<div class=\"type\">" . $this->getType($character) . "</div>"
                . "<div class=\"metier\">" . $this->getMetier($character) . "</div>"
                . "<div class=\"position\">" . $this->getPosition($character) . "</div>"
                . "<div class=\"links\">" . $this->getLinks($character) . "</div>"
                . "</div>" ;

        $res .= "</div></li>" ;
        return $res ;
    }
    
    
    public function getLinks($character) {
        return "" ;
    }
    
    public function getName($character) {
        return ""
                . $this->_name
                . $character->getName() . " "
                . $character->getConnectionImg("icone-inline")
                . "(" . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$character->id}", \I18n::DETAILS()) . ")" ;
    }
    
    public function getType($character) {
        return ""
                . $character->race->getName() . " "
                . $character->sex->getName() . " "
                . \I18n::LEVEL() . " " . $character->level ;
    }
    
    public function getMetier($character) {
        return $character->getHonorary() ;
    }
    
    public function getPosition($character) {
        return ""
                . \I18n::POSITION() . " : "
                . new \Quantyl\XML\Html\A(
                        "/Game/City?id={$character->position->id}",
                        $character->position->getName()
                        ) ;
    }
    
    
}
