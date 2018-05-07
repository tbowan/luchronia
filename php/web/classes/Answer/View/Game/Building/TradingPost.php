<?php

namespace Answer\View\Game\Building ;

class TradingPost extends Base {
        
    public function getSpecific() {
        $tradingpost = \Model\Game\Building\Tradingpost::GetFromInstance($this->_instance) ;
        
        return ""
                . new \Answer\Widget\Game\Building\TradingPost\Buyable($tradingpost)
                . new \Answer\Widget\Game\Building\TradingPost\Sellable($tradingpost)
                ;
    }
    
}
