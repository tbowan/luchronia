<?php

namespace Answer\Widget\Game\Building ;

class TradingSkills extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $classes = "") {
        
        parent::__construct(\I18n::SKILL_TRADING(), "", "", $this->getSkills($instance), $classes);
    }
    
    public function getSkills(\Model\Game\Building\Instance $instance) {
        $res = "" ;
        $res .= \I18n::SKILL_TRADING_MESSAGE() ;

        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SKILL(),
            \I18n::QUANTITY(),
            \I18n::BEST_PRICE(),
            \I18n::WORST_PRICE(),
            \I18n::ACTIONS()
        )) ;

        foreach (\Model\Game\Skill\Skill::getFromJob($instance->job) as $skill) {
            $summary = \Model\Game\Trading\Skill::getSummary($instance, $skill) ;
            $table->addRow(array(
                $skill->getImage("icone-inline") . " " . $skill->getName(),
                $summary["total"],
                $summary["best"],
                $summary["worst"],
                new \Quantyl\XML\Html\A("/Game/Building/TradingSkill/Show?instance={$instance->id}&skill={$skill->id}", \I18n::DETAILS())
            )) ;
        }
        $res .= $table ;

        $res .= "<p>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Building/TradingSkill/Mine?instance={$instance->id}", \I18n::SEE_SELL()) ;
        $res .= "</p>" ;

        return $res ;
    }
    
    
}
