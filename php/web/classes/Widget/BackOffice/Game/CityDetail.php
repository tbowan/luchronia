<?php

namespace Widget\BackOffice\Game ;

class CityDetail extends \Quantyl\Answer\Widget {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $city) {
        $this->_city = $city ;
    }
    
    public function getSummary() {
        $res  = "<h2>" . \I18n::CITY_SUMMARY() . "</h2>" ;
        $res .= "<ul>" ;
        $res .= "<li><strong>"
                . \I18n::CITY_NAME() 
                . " :</strong> {$this->_city->name} ("
                . new \Quantyl\XML\Html\A("/BackOffice/Game/ChangeCityName?id={$this->_city->id}", \I18n::EDIT())
                . ")</li>" ;
        $res .= "<li><strong>" . \I18n::COORDINATE()  . " :</strong> " . $this->_city->getGeoCoord() . "</li>" ;
        $res .= "<li><strong>" . \I18n::ALTITUDE()  . " :</strong> " . ($this->_city->altitude - 96 )*255 . " ". \I18n::METERS() . "</li>" ;
        $res .= "<li><strong>" . \I18n::ALBEDO()  . " :</strong> " . $this->_city->albedo . "</li>" ;
        
        $res .= "<li><strong>" 
                . \I18n::BIOME()  
                . " :</strong> " 
                . $this->_city->biome->getName() 
                . " ("
                . new \Quantyl\XML\Html\A("/BackOffice/Game/ChangeCityBiome?id={$this->_city->id}", \I18n::EDIT())
                . ")</li>" ;
                
        $res .= "<li><strong>" . \I18n::POLITICAL_SYSTEM()  . " :</strong> " . \Model\Game\Politic\System::LastFromCity($this->_city)->type->getName() . "</li>" ;
        
        $res .= "<li><strong>" . \I18n::SUN()  . " :</strong> " . $this->_city->sun . "</li>" ;
        $res .= "<li><strong>" . \I18n::MONSTER_IN()  . " :</strong> " . $this->_city->monster_in . "</li>" ;
        $res .= "<li><strong>" . \I18n::MONSTER_OUT()  . " :</strong> " . $this->_city->monster_out . "</li>" ;
        $res .= "<li><strong>" . \I18n::FITNESS()  . " :</strong> " . $this->_city->fitness . "</li>" ;
        $res .= "<li><strong>" . \I18n::SUNRISE()  . " :</strong> " . \I18n::_date_time($this->_city->sunrise - DT) . "</li>" ;
        $res .= "<li><strong>" . \I18n::SUNSET()  . " :</strong> " . \I18n::_date_time($this->_city->sunset - DT)  . "</li>" ;
        
        $res .= "<li><strong>" 
                . \I18n::CREDITS()  
                . " :</strong> " 
                . $this->_city->credits
                . " ("
                . new \Quantyl\XML\Html\A("/BackOffice/Game/City/GiveCredits?id={$this->_city->id}", \I18n::EDIT())
                . ")</li>" ;
        
        $res .= "</ul>" ;
        
        
        
        return "$res" ;
    }
    
    public function getBuildings() {
        $res = "<h2>" . \I18n::BUILDING_LIST() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::BUILDING_JOB(),
            \I18n::BUILDING_TYPE(),
            \I18n::BUILDING_LEVEL(),
            \I18n::BUILDING_HEALTH(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Building\Instance::GetAllFromCity($this->_city) as $instance) {
            $table->addRow(array(
                $instance->getImage("icone-inline") . $instance->getName(),
                $instance->type->getName(),
                $instance->level,
                $instance->getHealth(),
                new \Quantyl\XML\Html\A("/BackOffice/Game/Building/Edit?id={$instance->id}", \I18n::EDIT()) .
                new \Quantyl\XML\Html\A("/BackOffice/Game/Building/Delete?id={$instance->id}", \I18n::DELETE())
            )) ;
        }
        
        $table->addRow(array(
            \I18n::FREE_SLOT(), "-", "-", "-",
            new \Quantyl\XML\Html\A("/BackOffice/Game/Building/Create/?city={$this->_city->id}", \I18n::ADD())
        )) ;
        
        $res .= "$table" ;
        
        return $res ;
    }
    
    public function getContent() {
        
        return ""
        . $this->getSummary()
        . $this->getBuildings() ;
    }
    
    
}
