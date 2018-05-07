<?php

namespace Widget\Game\Ministry\Building ;

class TradingPost extends Base {
    
    public function getSpecific () {
        
        $tp = \Model\Game\Building\Tradingpost::GetFromInstance($this->_instance) ;
        
        $res = "<h2>" . \I18n::COMMERCIAL_ORDERS() . "</h2>" ;
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::TAX() . " : </strong>" . number_format($tp->tax * 100.0, 2) . " % " . new \Quantyl\XML\Html\A("/Game/Ministry/Building/TradingPost/Tax?tp={$tp->id}", \I18n::CHANGE_TAX()) . "</li>" ;
        $res .= "<li><strong>" . \I18n::ORDER_USED() . " : </strong>" . $tp->getTradingUsed() . " / " . $tp->getTradingMax() . "</li>" ;
        $res .= "</ul>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::TYPE(),
            \I18n::REMAIN_AMOUNT(),
            \I18n::PRICE(),
            \I18n::PUBLISHED(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Trading\Nation::GetFromNation(null) as $nation) {
            $order = \Model\Game\Trading\Tradingpost::GetOrder($this->_instance, $nation) ;
            $isbuy = $nation->type->equals(\Model\Game\Trading\Type::ToBuy()) ;
            $table->addRow(array(
                $nation->item->getImage("icone") . " " . $nation->item->getName() . " " . \I18n::HELP("/Help/Item?id={$nation->item->id}"),
                $nation->type->getName(),
                ($nation->amount === null ? "&#8734;" : $nation->amount),
                number_format($nation->price, 2),
                ($order !== null ? \I18n::YES_ICO() : \I18n::NO_ICO()),
                ($order !== null
                        ? new \Quantyl\XML\Html\A("/Game/Ministry/Building/TradingPost/Remove?order={$order->id}", \I18n::REMOVE())
                        : new \Quantyl\XML\Html\A("/Game/Ministry/Building/TradingPost/Add?nation={$nation->id}&instance={$this->_instance->id}", \I18n::ADD() ) )
                . ($isbuy
                        ? new \Quantyl\XML\Html\A("/Game/Ministry/Building/TradingPost/Buy?nation={$nation->id}&instance={$this->_instance->id}", \I18n::BUY() )
                        : new \Quantyl\XML\Html\A("/Game/Ministry/Building/TradingPost/Sell?nation={$nation->id}&instance={$this->_instance->id}", \I18n::SELL() )
                                )
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }
    
}
