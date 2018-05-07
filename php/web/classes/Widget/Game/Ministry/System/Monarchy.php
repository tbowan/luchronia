<?php

namespace Widget\Game\Ministry\System ;

class Monarchy extends Base {

    
    public function getSpecific() {
        $monarchy   = \Model\Game\Politic\Monarchy::GetFromSystem($this->_system) ;
        
        $res  = "<h3>" . ucfirst(\I18n::MONARCHY()) . " :</h3>" ;
        $res .= $this->_system->type->getDescription() ;
        $res .= "<ul>" ;
        $res .= "<li><strong>"
                . \I18n::KING()
                . " :</strong> "
                . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$monarchy->king->id}", $monarchy->king->getName())
                . "</li>" ;
        $res .= "</ul>" ;
        
        return $res ;
        
    }
    
    public function getQuestion() {
        return "" ;
    }
    
}
