<?php

namespace Answer\Widget\Game\Ministry\Commerce\Stock ;

class Building extends \Quantyl\Answer\Widget {
    
    private $_instance ;
    private $_admin ;
    
    public function __construct(\Model\Game\Building\Instance $inst, $admin) {
        $this->_instance = $inst ;
        $this->_admin = $admin ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . $this->_instance->getName() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::AMOUNT(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Ressource\City::GetFromInstance($this->_instance) as $st) {
            if ($this->_admin) {
                $actions = ""
                        . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Acquire?stock={$st->id}", \I18n::STOCK_TAKE())
                        . " / " . new \Quantyl\XML\Html\A("/Game/Ministry/GroupStock?stock={$st->id}", \I18n::STOCK_GROUP())
                        . " / " . new \Quantyl\XML\Html\A("/Game/Ministry/MoveStock?stock={$st->id}", \I18n::STOCK_MOVE()) ;
            } else {
                $actions = "" ;
            }
            
            $table->addRow(array(
                $st->item->getImage("icone-inline") . " " . $st->item->getName(),
                number_format($st->amount, 2),
                $actions
                )) ;
        }
        $res .= $table ;
        
        if ($table->getRowsCount() > 0) {
            return $res ;
        } else {
            return "" ;
        }
        
        
    }
    
}
