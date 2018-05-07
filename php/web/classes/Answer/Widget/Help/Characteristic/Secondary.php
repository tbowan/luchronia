<?php

namespace Answer\Widget\Help\Characteristic;

class Secondary extends \Answer\Widget\Misc\Card {

    public function __construct(\Model\Game\Characteristic $charac) {
        
        $res = \I18n::SECONDARY_CHARACTERISTIC_DETAIL_MSG() ;
        
        $res .= "<ul>" ;
        foreach (\Model\Game\Characteristic\Secondary::getFromSecondary($charac) as $b) {
            $base = $b->base ;
            $res .= "<li>" . $base->getImage("icone-inline") . " " . $base->getName() . \I18n::Help("/Help/Characteristic?id={$base->id}") . "</li>" ;
        }
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::SECONDARY_CHARACTERISTIC(), $res) ;
        
    }
}
