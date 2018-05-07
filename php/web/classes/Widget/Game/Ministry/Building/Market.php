<?php

namespace Widget\Game\Ministry\Building ;

class Market extends Base {
    
    public function getTradingTax() {
        $tax = \Model\Game\Tax\Tradable::GetFromInstance($this->_instance) ;
        return new \Answer\Widget\Misc\Card(
            \I18n::TRADING_TAX(),
            ""
            . \I18n::BUILDING_TRADING_TAX_MESSAGE($tax->order * 100, $tax->trans * 100)
            . new \Quantyl\XML\Html\A("/Game/Ministry/Tax/ChangeTradable?instance={$this->_instance->id}", \I18n::CHANGE_TAX())
            ) ;
    }
    
    
}
