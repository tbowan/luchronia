<?php

namespace Widget\Game\Character ;

class BuyableSkills extends \Quantyl\Answer\Widget {
    
    private $_char ;
    
    public function __construct(\Model\Game\Character $c) {
        $this->_char = $c ;
    }
    
    public function getContent() {
        
        $point = $this->_char->point ;
        
        $res = "" ;
        $res .= \I18n::BUYABLE_SKILL_MESSAGE($point) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SKILL(),
            \I18n::COST(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Skill\Skill::getBuyable($this->_char) as $s) {
            $table->addRow(array(
                $s->getImage("icone-inline") . " "
                . $s->getName() . " "
                . \I18n::Help("/Help/Skill?id={$s->id}"),
                $s->cost,
                new \Quantyl\XML\Html\A("/Game/LevelUp/NewSkill?skill={$s->id}", \I18n::BUY())
            )) ;
        }
        
        $res .= $table ;
        return $res ;
        
    }
    
}
