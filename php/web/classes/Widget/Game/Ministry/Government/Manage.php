<?php

namespace Widget\Game\Ministry\Government ;

class Manage extends \Quantyl\Answer\Widget {
    
    private $_gov ;
    
    public function __construct(\Model\Game\Politic\Government $g) {
        $this->_gov = $g ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::GOVERNMENT_COMPOSITION() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::MINISTRY(),
            \I18n::MINISTER(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Politic\Minister::getMinisters($this->_gov) as $minister) {
            $table->addRow(array(
                $minister->ministry->getImage("icone-inline") . " " . $minister->ministry->getName(),
                $minister->character->getName(),
                new \Quantyl\XML\Html\A("/Game/Ministry/Government/DelMinister?minister={$minister->id}", \I18n::DELETE())
            )) ;
        }
        
        $res .= $table ;
        
        $res .=  "<p>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Government/AddMinister?government={$this->_gov->id}", \I18n::ADD_MINISTER()) ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Government/Proceed?government={$this->_gov->id}", \I18n::PROCEED_GOVERNMENT()) ;
        $res .= "</p>" ;
        
        return $res ;
    }
}
