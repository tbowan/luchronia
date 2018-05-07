<?php

namespace Widget\Game\Map ;

class Cheat extends \Quantyl\Answer\Widget {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $c) {
        $this->_city = $c ;
    }
    
    public function getContent() {
        $name = $this->_city->getName() ;
        
        $res  = "<h2>" . \I18n::CITY_SUMMARY() . "</h2>";
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITY_NAME()        . \I18n::HELP("/Wiki/Nom de la ville") . " :</strong> {$name} </li>" ;
        $res .= "<li><strong>" . \I18n::COORDINATE()       . \I18n::HELP("/Wiki/Coordonnées") . " :</strong> " . $this->_city->getGeoCoord() . "</li>" ;
        $res .= "<li><strong>" . \I18n::BIOME()            . \I18n::HELP("/Wiki/Biome") . " :</strong> " . $this->_city->biome->getName() . "</li>" ;
        $res .= "</ul>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/Cheat/?city={$this->_city->id}", \I18n::MAP_CENTER_HERE()) ;
        
        $res .= "<h2>" . \I18n::CITY_MESSAGE() . "</h2>" ;
        $hasth = false ;
        foreach ($this->_city->getTownHalls() as $instance) {
            $hasth = true ;
            $townhall = \Model\Game\Building\TownHall::GetFromInstance($instance) ;
            $res .= "<h3>" . $townhall->name . "</h3>" ;
            $res .= $townhall->welcome ;
        }
        
        $res .= "<h2>" . \I18n::NATURAL_RESSOURCES() . "</h2>";
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(\I18n::ITEM(), \I18n::COEF())) ;
        foreach (\Model\Game\Ressource\Natural::GetFromCity($this->_city) as $nat) {
            $table->addRow(array(
                $nat->item->getImage("icone-med") . " " . $nat->item->getName(),
                number_format($nat->coef, 2)
            )) ;
        }
        $res .= $table ;
        
        return "$res" ;
    }
    
    public function isDecorable() {
        return false ;
    }
    
    
}