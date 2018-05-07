<?php

namespace Widget\Game\Ministry\Government ;

class Details extends \Quantyl\Answer\Widget {
    
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
        )) ;
        
        foreach (\Model\Game\Politic\Minister::getMinisters($this->_gov) as $minister) {
            $table->addRow(array(
                $minister->ministry->getImage("icone-inline") . " " . $minister->ministry->getName(),
                $minister->character->getName()
            )) ;
        }
        
        $res .= $table ;
        
        return $res;
    }
}
