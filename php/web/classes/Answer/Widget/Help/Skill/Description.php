<?php

namespace Answer\Widget\Help\Skill;

class Description extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Skill\Skill $s, $classes = "") {
        
        $res = ""
                . $this->_getIcon($s)
                . $this->_getDescription($s) ;
        
        parent::__construct(\I18n::DESCRIPTION() . " - " . $s->getName() , "", "", $res, $classes) ;
    }
    
    private function _getIcon(\Model\Game\Skill\Skill $s) {
        $res = "<div class=\"card-1-3\">" ;
        $res .= $s->getImage() ;
        $res .= "</div>" ;
        return $res ;
    }
    
    private function _getDescription(\Model\Game\Skill\Skill $s) {
        $res  = "<div class=\"card-2-3\">" ;
        
        $res .= $s->getDescription() ;
        
        $res .= "<ul>" ;
        $res .= $this->_getMetier($s) ;
        $res .= $this->_getCharact($s) ;
        $res .= $this->_getBuildingJob($s) ;
        $res .= $this->_getBuildingType($s) ;
        $res .= "</ul>" ;
        $res .= "</div>" ;
        return $res ;
    }
    
    private function _getMetier(\Model\Game\Skill\Skill $s) {
        return "<li>"
                        . "<strong>" . \I18n::METIER() . " : </strong>"
                        . new \Quantyl\XML\Html\A("/Help/Metier?id={$s->metier->id}", $s->metier->getName())
                        . "</li>" ;
    }
    
    private function _getCharact(\Model\Game\Skill\Skill $s) {
        return "<li>"
                . "<strong>" . \I18n::CHARACTERISTIC() . " : </strong>"
                . new \Quantyl\XML\Html\A("/Help/Characteristic?id={$s->characteristic->id}", $s->characteristic->getName())
                . "</li>" ;
    }
    
    public function _getBuildingType(\Model\Game\Skill\Skill $s) {
        $res = "<li><strong>" . \I18n::BUILDING_TYPE() . " : </strong>" ;
        if ($s->building_type === null) {
            $res .= \I18n::NONE() ;
        } else {
            $res .= new \Quantyl\XML\Html\A("/Help/Building/Type?id={$s->building_type->id}", $s->building_type->getName()) ;
        }
        $res .= "</li>" ;
        return $res ;
    }
    
    public function _getBuildingJob(\Model\Game\Skill\Skill $s) {
        $res = "<li><strong>" . \I18n::BUILDING_JOB() . " : </strong>" ;
        if ($s->building_job === null) {
            $res .= \I18n::NONE() ;
        } else {
            $res .= new \Quantyl\XML\Html\A("/Help/Building/Type?id={$s->building_job->id}", $s->building_job->getName()) ;
        }
        $res .= "</li>" ;
        return $res ;
    }

}
