<?php

namespace Answer\Widget\Game\Building\TradingSkill ;

class ShowMine extends \Quantyl\Answer\Widget {
    
    private $_instance ;
    private $_character ;
    
    public function __construct(
            \Model\Game\Building\Instance $i,
            \Model\Game\Character $me
            ) {
        parent::__construct() ;
        $this->_instance  = $i ;
        $this->_character = $me ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::SELL_SKILL_LIST() . "</h2>" ;
        
        $city = $this->_instance->city ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SKILL(),
            \I18n::TOTAL(),
            \I18n::REMAIN(),
            \I18n::PRICE_TTC(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Trading\Skill::getForCharacter($this->_instance, $this->_character) as $sell) {
            $table->addRow(array(
                $sell->skill->getName(),
                $sell->total,
                $sell->remain,
                $sell->price,
                new \Quantyl\XML\Html\A("/Game/Building/TradingSkill/Close?sell={$sell->id}", \I18n::CLOSE_SELL())
            )) ;
        }
        $res .= $table ;
        
        return $res ;
    }
    
}
