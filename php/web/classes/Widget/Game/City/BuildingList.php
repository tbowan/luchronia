<?php

namespace Widget\Game\City ;

use Model\Game\Building\Instance;
use Model\Game\City;
use Quantyl\Answer\Widget;
use Quantyl\XML\Html\A;
use Quantyl\XML\Html\Img;
use Quantyl\XML\Html\Table;

class BuildingList extends Widget {
    
    private $_city ;
    private $_character ;
    
    public function __construct(City $city, \Model\Game\Character $character) {
        $this->_city      = $city ;
        $this->_character = $character ;
    }
    
    private function getAdminLink(Instance $inst) {
        
        if (\Model\Game\Politic\Minister::hasPower(
                    $this->_character,
                    $this->_city,
                    $inst->job->ministry)) {
            return new A("/Game/Ministry/Building?instance={$inst->id}", \I18n::ADMINISTER()) ;
        } else {
            return "" ;
        }
    }
    
    public function addOutSide(Table &$table) {
        $job = \Model\Game\Building\Job::GetByName("OutSide") ;
        $table->addRow(array(
            new Img("/Media/icones/Model/Building/OutSide.png", $job->getName(), "icone-med"),
            "<strong>" . $job->getName() . "</strong>",
            new A("/Game/Building/OutSide?city={$this->_city->id}", \I18n::SEE())
        )) ;
    }
    
    public function addBuildings(Table &$table) {
        
        if ($this->_city->canEnter($this->_character)) {

            foreach (Instance::GetFromCity($this->_city) as $instance) {
                $table->addRow(array(
                    $instance->getImage("icone-med"),
                    "<strong>" . $instance->getName() . "</strong><br/>"
                    . $instance->type->getName() . ", "
                    . \I18n::LEVEL() . " : " . $instance->level. ", "
                    . \I18n::HEALTH() . " : " . $instance->getHealth()
                    . " + " . number_format($instance->barricade, 2),
                    new A("/Game/Building/?id={$instance->id}", \I18n::SEE())
                    . $this->getAdminLink($instance)
                )) ;
            }


            if ($table->getRowsCount() == 1) {
                return \I18n::NO_BUILDING() ;
            } else {
                return "" ;
            }
        } else {
            return \I18n::CITY_WALL_ARE_CLOSED() ;
        }
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::BUILDING_LIST() . "</h2>" ;
        
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ICONE(),
            \I18n::BUILDING(),
            \I18n::ACTIONS()
        )) ;
        $this->addOutSide($table) ;
        $res .= $this->addBuildings($table) ;
        
        $res .= $table ;

        
        return $res ;
        
    }
    
    
}
