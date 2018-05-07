<?php

namespace Answer\Widget\Game\Ressource ;

class Food extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        list ($i_e, $i_h) = \Model\Game\Ressource\Inventory::TotalEnergyHydration($me) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array("", \I18n::POINTS(), \I18n::DAYS())) ;
        $table->addRow(array(\I18n::ENERGY(), $i_e, round($i_e / 720, 2))) ;
        $table->addRow(array(\I18n::HYDRATION(), $i_h, round($i_h / 720, 2))) ;
        
        parent::__construct(\I18n::FOOD_EQUIVALENT(), new \Quantyl\XML\Html\A("/Game/Inventory/Feed", \I18n::ITEM_FEED()), "", "$table", $classes) ;
    }
    
    
    
}
