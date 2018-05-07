<?php

namespace Answer\View\Game\Ministry ;

use Model\Game\Building\Instance;
use Quantyl\XML\Html\A;
use Quantyl\XML\Html\Img;
use Quantyl\XML\Html\Table;

class Development extends Base {

    
    public function getPrices($classes = "") {
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::PRICE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Ressource\Item::GetMayBeNeeded() as $item) {
            $p = \Model\Game\Trading\Needed::GetPrice($item, $this->_city) ;
            if ($this->_isadmin) {
                $actions = new A("/Game/Ministry/Development/FixPriceNeeded?city={$this->_city->id}&item={$item->id}", \I18n::FIX_PRICE()) ;
            } else {
                $actions = "" ;
            }
            $table->addRow(array(
                $item->getImage("icone-inline") . " "
                . $item->getName() . " "
                .\I18n::HELP("/Help/Item?id={$item->id}"),
                number_format($p, 2),
                $actions
                )) ;
        }
        $res .= "$table" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::PRICE_NEEDED(), "", "", $res, $classes) ;        
    }
    
    public function getSpecificBuildings($classes  = "") {
        $res = "" ;
        
        $slots = $this->_city->getSlotCount() ;
        
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ICONE(),
            \I18n::BUILDING(),
            \I18n::ACTIONS()
        )) ;
        
        $cnt = 0 ;
        foreach (Instance::GetFromCity($this->_city) as $instance) {
            if ($this->_isadmin) {
                $actions = ""
                        . new A("/Game/Ministry/Development/Delete?instance={$instance->id}", \I18n::BUILDING_DESTROY()) . "<br/>"
                        . new A("/Game/Ministry/Development/Upgrade?instance={$instance->id}", \I18n::BUILDING_UPGRADE()) . "<br/>"
                        . new A("/Game/Ministry/Development/Restore?instance={$instance->id}", \I18n::BUILDING_RESTORE()) . "<br/>"
                    ;
            } else {
                $actions = "" ;
            }
            $table->addRow(array(
                $instance->getImage("icone-med"),
                "<strong>" . $instance->getName() . "</strong><br/>"
                . $instance->type->getName() . ", "
                . \I18n::LEVEL() . " : " . $instance->level. "<br/>"
                . \I18n::HEALTH() . " : " . $instance->getHealth()
                . " + " . number_format($instance->barricade, 2),
                $actions . new A("/Game/Building/?id={$instance->id}", \I18n::SEE())
            )) ;
            $cnt += $instance->type->slot ;
        }
        
        if ($cnt < $slots) {
            $icone = new Img("/Media/icones/Model/Building/Free.png", \I18n::FREE_SLOT()) ;
            $icone->setAttribute("class", "icone-med") ;
            $table->addRow(array(
                $icone,
                \I18n::FREE_SLOT(),
                new A("/Game/Building/FreeSlot?city={$this->_city->id}", \I18n::SEE())
                )) ;
            $res .= \I18n::DEVELOPMENT_REMAIN_SLOT($slots-$cnt) ;
        }
        
        $res .= $table ;
        
        if (! Instance::HasCityJob($this->_city, \Model\Game\Building\Job::GetByName("Road"))) {
            $res .= new A("/Game/Ministry/Development/Create/Road?city={$this->_city->id}", \I18n::CREATE_ROAD()) ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::BUILDING_LIST(), "", "", $res, $classes) ;
    }
    
    public function getSpecific() {
        return ""
                . $this->getSpecificBuildings()
                . $this->getPrices() ;
    }
    
}
