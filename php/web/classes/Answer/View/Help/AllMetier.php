<?php

namespace Answer\View\Help ;

class AllMetier extends \Answer\View\Base {
    
    private $_viewer ;
    
    public function __construct($service, $v) {
        parent::__construct($service) ;
        $this->_viewer  = $v ;
    }
    
    public function addMinistry(\Model\Game\Politic\Ministry $ministry) {
        $res = "<h2>" . $ministry->getName() . "</h2>" ;
        $has = false ;
        $res .= "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Skill\Metier::getFromMinistry($ministry) as $metier) {
            $uses = ($this->_viewer === null ? 0 : $metier->getUse($this->_viewer)) ;
            
            $res .= "<li class=\"card-1-5\"><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" .  $metier->getMedalImg($this->_viewer, "", $uses) . "</div>" ;
            $res .= "<div class=\"description\">" ;
            $res .= "<div class=\"name\">" . ucfirst($metier->getName()) . "</div>" ;
            $res .= "<div class=\"links\">" . \I18n::HELP_MSG("/Help/Metier?id={$metier->id}") . "</div>" ;
            $res .= "</div>" ;
            $res .= "</div></li>" ;
            $has = true ;
            
        }
        $res .= "</ul>" ;
        
        if ($has) {
            return $res ;
        } else {
            return "" ;
        }
        
    }
    
    public function getTplContent() {
        $res = \I18n::HELP_ALLMETIER_MESSAGE() ;
        
        foreach (\Model\Game\Politic\Ministry::GetAll() as $ministry) {
            $res .= $this->addMinistry($ministry) ;
        }
        return new \Answer\Widget\Misc\Section(\I18n::METIER_LIST(), "", "", $res, "") ;
        
    }
    
}
