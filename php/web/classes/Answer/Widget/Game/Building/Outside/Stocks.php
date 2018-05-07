<?php

namespace Answer\Widget\Game\Building\Outside ;

class Stocks extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\City $city, $isadmin, $classes = "") {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::AMOUNT(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Ressource\City::getPublicStocks($city) as $st) {
            $table->addRow(array(
                $st->item->getImage("icone-inline") . " " . $st->item->getName(),
                number_format($st->amount, 2),
                new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Take?stock={$st->id}", \I18n::STOCK_TAKE())
                . ($isadmin
                        ? " / " . new \Quantyl\XML\Html\A("/Game/Ministry/MoveStock?stock={$st->id}", \I18n::STOCK_MOVE())
                        . " / " . new \Quantyl\XML\Html\A("/Game/Ministry/GroupStock?stock={$st->id}", \I18n::STOCK_GROUP())
                        : "")
                )) ;
        }
        
        $res = "" ;
        $res .= $table ;
        $res .= "<ul>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Register?city={$city->id}", \I18n::SEE_REGISTER()) . "</li>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Give?city={$city->id}", \I18n::STOCK_GIVE()) . "</li>" ;
        $res .= "</ul>" ;
        
        parent::__construct(
                \I18n::STOCK_LIST_PUBLIC(),
                "",
                "",
                $res,
                "");
       
    }
    
}
