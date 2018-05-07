<?php

namespace Widget\BackOffice\Treasure ;

class AllTreasures extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        
        $res = "" ;
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Treasure/Add", \I18n::ADD()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::BUILDING_JOB(),
            \I18n::BUILDING_TYPE(),
            \I18n::BIOME(),
            \I18n::CITY(),
            \I18n::ITEM(),
            \I18n::AMOUNT(),
            \I18n::INFINITE(),
            \I18n::GAIN(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Ressource\Treasure::GetAll() as $t) {
            $table->addRow(array(
                $t->job == null ? "-" : $t->job->getName(),
                $t->type == null ? "-" : $t->type->getName(),
                $t->biome == null ? "-" : $t->biome->getName(),
                $t->city == null ? "-" : $t->city->getName(),
                $t->item->getImage("icone-inline") . " " . $t->item->getName() . " " . \I18n::HELP("/Help/Item?id={$t->item->id}"),
                $t->amount,
                $t->infinite ? \I18n::YES_ICO() : \I18n::NO_ICO(),
                $t->gained,
                new \Quantyl\XML\Html\A("/BackOffice/Treasure/Edit?id={$t->id}", \I18n::EDIT()) . " " .
                new \Quantyl\XML\Html\A("/BackOffice/Treasure/Delete?id={$t->id}", \I18n::DELETE())
                
            )) ;
        }
        
        $res .= $table ;
        return $res ;
    }
    
}
