<?php

namespace Widget\BackOffice\Game ;

class Item extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        $res = "<h2>" . \I18n::BUYABLE_ITEMS() . "</h2>" ;
        
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Game/Item/Add", \I18n::ADD()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::QUANTA_PER_ENERGY(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Ressource\Item::GetBuyable() as $item) {
            $table->addRow(array(
                $item->getImage("icone") . " " . $item->getName(),
                number_format($item->price / $item->energy, 2),
                $item->price,
                new \Quantyl\XML\Html\A("/BackOffice/Game/Item/Edit?item={$item->id}", \I18n::EDIT())
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }
    
}
