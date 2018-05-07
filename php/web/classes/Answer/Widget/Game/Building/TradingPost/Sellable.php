<?php

namespace Answer\Widget\Game\Building\TradingPost ;

class Sellable extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Tradingpost $tradingpost, $classes = "") {
        
        $res = "<ul class=\"itemList\">" ;
        
        foreach (\Model\Game\Trading\Tradingpost::GetFromInstanceAndType($tradingpost->instance, \Model\Game\Trading\Type::ToSell()) as $order) {
            $nation = $order->trading ;
            $qtty  = ($nation->amount === null ? "&#8734;" : $nation->amount) ;
            $sell  = new \Quantyl\XML\Html\A("/Game/Building/TradingPost/Sell?order={$order->id}", \I18n::SELL()) ;
            $price = number_format($nation->price * (1 + $tradingpost->tax), 2) ;
                    
            $res .= "<li class=\"card-1-2\"><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $nation->item->getImage() . "</div>" ;
            $res .= "<div class=\"description\">"
                    . "<div class=\"name\">" . $nation->item->getName() . " ( $qtty )</div>"
                    . "<div class=\"links\">$sell : $price " . \I18n::CREDITS_ICO() . "</div>"
                    . "</div>" ;
            $res .= "</div></li>" ;
            
        }
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::ITEM_TO_SELL(), "", "", $res, $classes);
    }
    
    
    
}
