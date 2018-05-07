<?php

namespace Answer\View\Game\System ;

abstract class Base extends \Answer\View\Base {
    
    protected $_system ;
    protected $_viewer ;
    protected $_canmanage ;
    
    public function __construct($service, \Model\Game\Politic\System $system, \Model\Game\Character $character) {
        parent::__construct($service) ;
        $this->_system      = $system ;
        $this->_viewer      = $character ;
        $this->_canmanage   = $this->_system->canManage($this->_viewer) ;
    }
    
    public function getTplContent() {
        $system     = $this->_system ;
        
        return ""
                . $this->_getInfo("card-1-2")
                . new \Answer\Widget\Game\Ministry\Dgap\System($system, $this->_canmanage, "card-1-2")
                . $this->getSpecific() ;
                ;
    }
    
    public function _getInfo($classes = "") {
        $res = "" ;
        $res .= "<div class=\"card-1-3\">" . $this->_system->type->getImage("full") . "</div>" ;
        $res .= "<div class=\"card-2-3\">" .  $this->_system->type->getDescription() . "</div>" ;
        return new \Answer\Widget\Misc\Section($this->_system->type->getName(), "", "", $res, $classes) ;
    }

    public function getSpecific() {
        return "" ;
    }
    
    
    
}
