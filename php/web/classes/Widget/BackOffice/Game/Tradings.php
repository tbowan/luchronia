<?php

namespace Widget\BackOffice\Game ;

class Tradings extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        
        $res = "" ;
        
        $res = new \Quantyl\XML\Html\A("/BackOffice/Game/Trading/Add", \I18n::ADD_TRADING()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::TYPE(),
            \I18n::REMAIN_AMOUNT(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Trading\Nation::GetFromNation(null) as $nation) {
            $table->addRow(array(
                $nation->item->getImage("icone") . " " . $nation->item->getName() . " " . \I18n::HELP("/Help/Item?id={$nation->item->id}"),
                $nation->type->getName(),
                ($nation->amount === null ? "&#8734;" : $nation->amount),
                number_format($nation->price, 2),
                new \Quantyl\XML\Html\A("/BackOffice/Game/Trading/Edit?order={$nation->id}", \I18n::EDIT()) .
                new \Quantyl\XML\Html\A("/BackOffice/Game/Trading/Delete?order={$nation->id}", \I18n::DELETE())
            )) ;
        }
        
        $res .= $table ;
        return $res ;
    }
    
}
