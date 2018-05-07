<?php

namespace Answer\View\Game\Ministry ;

use Model\Game\Building\Instance;
use Model\Game\Character;
use Model\Game\City;
use Model\Game\Politic\Minister;
use Model\Game\Politic\Ministry;
use Quantyl\XML\Html\A;
use Quantyl\XML\Html\Table;

class Base extends \Answer\View\Base {
    
    protected $_city ;
    protected $_ministry ;
    protected $_character ;
    protected $_isadmin ;
    
    public function __construct($service, City $city, Ministry $ministry, Character $character ) {
        parent::__construct($service);
        
        $this->_city      = $city ;
        $this->_ministry  = $ministry ;
        $this->_character = $character ;
        $this->_isadmin = Minister::hasPower($character, $city, $ministry) ;
    }
    
    public function getInformations($classes = "") {
        $res  = "<div class=\"card-1-3\">" . $this->_ministry->getImage("illustration") . "</div>" ;
        $res .= "<div class=\"card-2-3\">" . $this->_ministry->getDescription()         . "</div>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::MINISTRY_SUMMARY(), "", "", $res, $classes) ;
    }
    
    public function getBuildings($classes = "") {
        
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ICONE(),
            \I18n::BUILDING(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (Instance::GetFromCity($this->_city) as $instance) {
            if ($this->_ministry->equals($instance->job->ministry)) {
                $table->addRow(array(
                    $instance->getImage("icone-med"),
                    "<strong>" . $instance->getName() . "</strong><br/>"
                    . $instance->type->getName() . ", "
                    . \I18n::LEVEL() . " : " . $instance->level. ", "
                    . \I18n::HEALTH() . " : " . $instance->getHealth(),
                    new A("/Game/Building/?id={$instance->id}", \I18n::SEE())
                    . ($this->_isadmin ? new A("/Game/Ministry/Building?instance={$instance->id}", \I18n::ADMINISTER()) : "")
                )) ;
            }
        }
        
        if ($table->getRowsCount() == 0) {
            $res = \I18n::MINISTRY_NO_BUILDING() ;
        } else {
            $res = "$table" ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::MINISTRY_BUILDINGS(), "", "", $res, $classes) ;
    }
    
    public function getMetier($classes = "") {
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::ICONE(),
            \I18n::METIER(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Game\Skill\Metier::GetFromMinistry($this->_ministry) as $metier) {
            $table->addRow(array(
                $metier->getMedalImg($this->_character, "icone-med"),
                "<strong>" . $metier->getName() . "</strong>",
                new A("/Help/Metier?id={$metier->id}", \I18n::SEE())
                . ($this->_isadmin ? new A("/Game/Ministry/Metier?metier={$metier->id}&city={$this->_city->id}", \I18n::ADMINISTER()) : "")
            )) ;
        }
        
        if ($table->getRowsCount() == 0) {
            $res = \I18n::MINISTRY_NO_METIER() ;
        } else {
            $res = "$table" ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::MINISTRY_METIERS(), "", "", $res, $classes) ;
        
        return $res ;
    }
    
    public function getSpecific() {
        return "" ;
    }
    
    public function getTplContent() {
        return ""
                . "<div class=\"card-1-2\">"
                . $this->getInformations()
                . $this->getBuildings()
                . $this->getMetier()
                . "</div>"
                . "<div class=\"card-1-2\">"
                . $this->getSpecific()
                . "</div>"
            ;
    }
    
}
