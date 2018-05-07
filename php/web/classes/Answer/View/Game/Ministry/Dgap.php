<?php

namespace Answer\View\Game\Ministry ;

class Dgap extends \Answer\View\Base {
    
    private $_city ;
    private $_viewer ;
    
    public function __construct($service, $city, $viewer) {
        parent::__construct($service);
        $this->_city   = $city ;
        $this->_viewer = $viewer ;
    }
    
    public function getTplContent() {
        
        $system     = \Model\Game\Politic\System::LastFromCity($this->_city) ;
        $canmanage  = $system->canManage($this->_viewer) ;
        
        return ""
                . "<div class=\"card-1-2\">"
                . $this->_getInfo()
                . new \Answer\Widget\Game\Ministry\Dgap\System($system, $canmanage)
                . new \Answer\Widget\Game\Ministry\Dgap\Revolution($system, $this->_viewer)
                . "</div>" 
                . "<div class=\"card-1-2\">"
                . $this->_getSpecific($system)
                . "</div>"
                ;
    }
    
    private function _getInfo($classes = "") {
        $res = "" ;
        $res .= "<div class=\"card-1-3\">" . \I18n::DGAP_IMAGE("") . "</div>" ;
        $res .= "<div class=\"card-2-3\">" .  \I18n::DGAP_DESCRIPTION() . "</div>" ;
        return new \Answer\Widget\Misc\Section(\I18n::MINISTRY_SUMMARY(), "", "", $res, $classes) ;
    }
    
    private function _getSpecific(\Model\Game\Politic\System $system) {
        $funcname = "_get" . $system->type->getValue() ;
        return $this->$funcname($system) ;
    }
    
    private function _getAnarchy(\Model\Game\Politic\System $system) {
        return new \Answer\Widget\Misc\Section($system->type->getName(), "", "", $system->type->getDescription(), "") ;
    }
    
    private function _getMonarchy(\Model\Game\Politic\System $system) {
        return ""
                . new \Answer\Widget\Game\Ministry\Dgap\Government($system, $this->_viewer)
                ;
    }
    
    private function _getSenate(\Model\Game\Politic\System $system) {
        return ""
                . new \Answer\Widget\Game\Ministry\Dgap\Government($system, $this->_viewer)
                . new \Answer\Widget\Game\Ministry\Dgap\Question($system, $this->_viewer)
                ;
    }
    
    private function _getRepublic(\Model\Game\Politic\System $system) {
        return ""
                . new \Answer\Widget\Game\Ministry\Dgap\Government($system, $this->_viewer)
                . new \Answer\Widget\Game\Ministry\Dgap\Question($system, $this->_viewer)
                ;
    }
    
    private function _getParliamentary(\Model\Game\Politic\System $system) {
        return ""
                . new \Answer\Widget\Game\Ministry\Dgap\Government($system, $this->_viewer)
                . new \Answer\Widget\Game\Ministry\Dgap\Question($system, $this->_viewer)
                ;
    }
    
    private function _getDemocracy(\Model\Game\Politic\System $system) {
        return ""
                . new \Answer\Widget\Game\Ministry\Dgap\Government($system, $this->_viewer)
                . new \Answer\Widget\Game\Ministry\Dgap\Question($system, $this->_viewer)
                ;
    }
    
    private function _getRevolution(\Model\Game\Politic\System $system) {
        return ""
                . new \Answer\Widget\Game\Ministry\Dgap\Support($system, $this->_viewer)
                ;
    }
    
}
