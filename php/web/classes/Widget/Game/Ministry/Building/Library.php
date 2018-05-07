<?php


namespace Widget\Game\Ministry\Building ;

class Library extends Base {
    
    public function getStockAction(\Model\Game\Ressource\City $st) {
        $res = "" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Acquire?stock={$st->id}", \I18n::STOCK_TAKE()) ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/MoveStock?stock={$st->id}", \I18n::STOCK_MOVE()) ;
        $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/SetTarif?stock={$st->id}", \I18n::STOCK_SETTARIF()) ;
        return $res ;
    }
    
    public function getBuildingStock() {
        $res = "<h2>" . \I18n::BUILDING_STOCKS() . "</h2>" ;
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEMS(),
            \I18n::AMOUNT(),
            \I18n::PUBLISHED(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
                )) ;
        
        foreach (\Model\Game\Ressource\City::GetFromInstance($this->_instance) as $st) {
            $table->addRow(array(
                $st->item->getImage("icone-med") . " " . $st->item->getName(),
                $st->amount,
                ($st->published ? \I18n::YES_ICO() : \I18n::NO_ICO()),
                ($st->price == null ? "-" : $st->price),
                $this->getStockAction($st)
            )) ;
        }
        
        if ($table->getRowsCount() > 0) {
            $res .= $table ;
            return $res ;
        } else {
            return "" ;
        }
    }
    
}
