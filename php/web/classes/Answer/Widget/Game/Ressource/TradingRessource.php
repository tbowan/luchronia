<?php

namespace Answer\Widget\Game\Ressource ;

class TradingRessource extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::TRADING_LOCATION(),
            \I18n::RESSOURCE(),
            \I18n::TOTAL(),
            \I18n::REMAIN(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Trading\Character\Market\Sell::GetMySells($me) as $sell) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/Game/Building?id={$sell->market->id}",
                        $sell->market->getName() . " - " . $sell->market->city->getName()),
                $sell->ressource->getName(),
                $sell->total,
                $sell->remain,
                $sell->price,
                new \Quantyl\XML\Html\A("/Game/Building/Market/CloseSell?sell={$sell->id}", \I18n::CLOSE_SELL())
            )) ;
        }
        
        parent::__construct(\I18n::MY_TRADING_RESSOURCE(), "", "", "$table", $classes);
    }
    
}
