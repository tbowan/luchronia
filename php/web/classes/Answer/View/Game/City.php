<?php

namespace Answer\View\Game ;

class City extends \Answer\Decorator\Game {
    
    private $_city ;
    private $_character ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, \Model\Game\City $city, \Model\Game\Character $me) {
        parent::__construct($service, "") ;
        $this->_city = $city ;
        $this->_character = $me ;
        
    }
    
    public function getNoTownHall() {
        
        $takecontrol = new \Quantyl\XML\Html\A("/Game/Ministry/TakeControl&city={$this->_city->id}", \I18n::NOTH_TAKE_CONTROL()) ;
        $ministry    = new \Quantyl\XML\Html\A("/Game/Ministry/?ministry=Development&city={$this->_city->id}", \I18n::NOTH_DEVELOP()) ;
        
        $res = \I18n::CITY_NOTH_MESSAGE(
                $takecontrol, $ministry) ;
        
        return new \Answer\Widget\Misc\Section(\I18n::CITY_NOTH_TITLE(), "", "", $res) ;
    }
    
    public function getMessages() {
        $res = "" ;
        
        $hasth = false ;
        foreach ($this->_city->getTownHalls() as $instance) {
            $hasth = true ;
            $townhall = \Model\Game\Building\TownHall::GetFromInstance($instance) ;
            $res .= new \Answer\Widget\Game\City\Message($townhall) ;
        }

        if ($hasth) {
            return $res ;
        } else {
            return $this->getNoTownHall() ;
        }
    }
    
    private function canManage($instance) {
        $res = \Model\Game\Politic\Minister::hasPower(
                            $this->_character,
                            $this->_city,
                            $instance->job->ministry) ;
        return $res ;
    }
    
    public function getBuildings() {
        
        $city = $this->_city ;
        
        $msg  = "<ul class=\"itemList\">" ;
        $msg .= new \Answer\Widget\Game\City\OutdoorCard($city) ;
        foreach (\Model\Game\Building\Instance::GetFromCity($city) as $instance) {
            $msg .= "<li>" . new \Answer\Widget\Game\City\BuildingCard($instance, $instance->canManage($this->_character)) . "</li>" ;
        }
        $msg .= "</ul>" ;
        
        
        return new \Answer\Widget\Misc\Section(
                \I18n::BUILDING_LIST(),
                "", "",
                $msg) ;
    }
    
    public function getOutDoor() {
        $job = \Model\Game\Building\Job::GetByName("OutSide") ;
        
        $res = "<li><div class=\"item\">" ;
        
        $res .= "<div class=\"icon\">" ;
        $res .= new \Quantyl\XML\Html\Img("/Media/icones/Model/Building/OutSide.png", $job->getName(), "icone-med") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"description\">" ;
        $res .= "<div class=\"name\">" . $job->getName() . "</div>" ;
        $res .= "<div class=\"monsters\">" . \I18n::MONSTERS() . " : " . number_format($this->_city->monster_out) . "</div>" ;
        $res .= "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/Building/OutSide?city={$this->_city->id}", \I18n::SEE()) . "</div>" ;
        $res .= "</div>" ;
        
        $res .= "</div></li>" ;
        return $res ;
    }
    
    public function getTplContent() {
        return ""
                . '<div class="card-1-2 left">'
                . $this->getMessages()
                . new \Answer\Widget\Game\City\Geography($this->_city)
                . new \Answer\Widget\Game\City\Social($this->_city)
                . '</div>'
                . '<div class="card-1-2 right">'
                . $this->getBuildings()
                . '</div>'
                ;
    }

    public function getTplTitle() {
        return $this->_city->getName() ;
    }

}
