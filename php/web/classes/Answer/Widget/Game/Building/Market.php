<?php

namespace Answer\Widget\Game\Building ;

class Market extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $classes = "") {
        
        $tax_bdd = \Model\Game\Tax\Tradable::GetFromInstance($instance) ;
        $tax = 1.0 + $tax_bdd->trans ;
        
        $res = "" ;
        
        
        $res .= "<ul class=\"itemList\">" ;
        
        foreach (\Model\Game\Trading\Character\Market\Sell::getRessources($instance) as $row) {
            $item = \Model\Game\Ressource\Item::GetById($row["item"]) ;
            
            
            $res .= "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $item->getImage() . "</div>" ;
            $res .= "<div class=\"description\">"
                    . "<div class=\"name\">" . $item->getName() . "</div>"
                    . "<div class=\"amount\">" . \I18n::AMOUNT() . " : " . $row["quantity"] . "</div>"
                    . "<div class=\"price\">" . \I18n::PRICE() . " : " . number_format($row["best"] * $tax, 2) . " - " . number_format($row["worst"] * $tax, 2). "</div>"
                    . "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Building/Market/ShowSell?instance={$instance->id}&ressource={$item->id}", \I18n::DETAILS()) . "</div>"
                    . "</div>" ;
            $res .= "</div></li>" ;
            
        }
        $res .= "</ul>" ;
        
        
        $head = new \Quantyl\XML\Html\A("/Game/Building/Market/AddSell?instance={$instance->id}", \I18n::SELL()) ;
        $head .= " / " ;
        $head .= new \Quantyl\XML\Html\A("/Game/Building/Market/MySell?instance={$instance->id}", \I18n::SEE_SELL()) ;
        
        
        parent::__construct(\I18n::ITEM_TO_BUY(), $head, "", $res, $classes);
    }
    
}
