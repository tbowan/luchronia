<?php

namespace Answer\Widget\Game\Ressource ;

class TradingSkill extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::TRADING_LOCATION(),
            \I18n::SKILL(),
            \I18n::TOTAL(),
            \I18n::REMAIN(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Trading\Skill::GetMine($me) as $sell) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Building?id={$sell->instance->id}",
                        $sell->instance->getName() . " - " . $sell->instance->city->getName()),
                $sell->skill->getName(),
                $sell->total,
                $sell->remain,
                $sell->price,
                new \Quantyl\XML\Html\A("/Game/Building/TradingSkill/Close?sell={$sell->id}", \I18n::CLOSE_SELL())
            )) ;
        }
        
        parent::__construct(\I18n::MY_TRADING_SKILL(), "", "", "$table", $classes);
    }
    
}
