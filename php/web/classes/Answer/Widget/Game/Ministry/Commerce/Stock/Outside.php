<?php

namespace Answer\Widget\Game\Ministry\Commerce\Stock ;

class Outside extends \Quantyl\Answer\Widget {
    
    private $_city ;
    private $_admin ;
    
    public function __construct(\Model\Game\City $city, $admin) {
        $this->_city  = $city ;
        $this->_admin = $admin ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::STOCK_LIST_PUBLIC() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::AMOUNT(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Ressource\City::getPublicStocks($this->_city) as $st) {
            $table->addRow(array(
                $st->item->getImage("icone-inline") . " " . $st->item->getName(),
                number_format($st->amount, 2),
                new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Take?stock={$st->id}", \I18n::STOCK_TAKE())
                . ($this->_admin
                        ? " / " . new \Quantyl\XML\Html\A("/Game/Ministry/MoveStock?stock={$st->id}", \I18n::STOCK_MOVE())
                        . " / " . new \Quantyl\XML\Html\A("/Game/Ministry/GroupStock?stock={$st->id}", \I18n::STOCK_GROUP())
                        : "")
                )) ;
        }
        $res .= $table ;
        $res .= "<ul>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Register?city={$this->_city->id}", \I18n::SEE_REGISTER()) . "</li>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Give?city={$this->_city->id}", \I18n::STOCK_GIVE()) . "</li>" ;
        $res .= "</ul>" ;
        
        return $res ;
        
    }
    
}
