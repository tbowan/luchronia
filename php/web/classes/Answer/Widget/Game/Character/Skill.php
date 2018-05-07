<?php

namespace Answer\Widget\Game\Character ;

class Skill extends \Answer\Widget\Misc\Section  {
    
    public function __construct(\Model\Game\Character $c, $myself, $classes = "") {
        parent::__construct(\I18n::SKILLS(), "", "", $this->getMetiers($c, $myself), $classes) ;
    }
    
    private function feedMetiers($c, &$metiers, &$skills) {
        foreach (\Model\Game\Skill\Character::GetFromCharacter($c) as $cs) {
            $id = $cs->skill->metier->id ;
            if (! isset($metiers[$id])) {
                $metiers[$id] = $cs->skill->metier ;
                $skills[$id] = array() ;
            }
            $skills[$id][] = $cs ;
        }
    }
    
    private function getMetiers($c, $myself) {
        $metiers = array() ;
        $skills  = array() ;
        
        $this->feedMetiers($c, $metiers, $skills) ;
        
        $res = "" ;
        foreach ($metiers as $id => $metier) {
            $lvl = $metier->getLevel($c) ;
            $res .= "<h2>" . $metier->getName() . " (" . \I18n::LEVEL() . " $lvl)</h2>" ;
            // add medal and level
            $res .= "<ul class=\"itemList\">" ;
            foreach ($skills[$id] as $cs) {
                $res .= "<li class=\"card-1-2\">" . $this->getSkillLi($cs, $myself) . "</li>" ;
            }
            $res .= "</ul>" ;
        }
        return $res ;
        
    }
    
    private function getSkillLi(\Model\Game\Skill\Character $cs, $myself) {
        
        $max = $cs->getLearningThreshold() ;
        $val = $cs->learning ;
        $cst = ceil(($max - $val) / 100.0) ;
        $upg = ($cs->level >= 0) ;
            
        
        $msg = "<div class=\"item\">" ;
        
        $msg .= "<div class=\"name\">"
                    . $cs->skill->getName() 
                    . "</div>" ;
        
        
        $msg .= "<div class=\"icon\">" ;
        $msg .= $cs->skill->getImage() ;
        $msg .= "</div>" ;
        
        $msg .= "<div class=\"description\">" ;
        $msg .= "<div class=\"level\">" 
                    . \I18n::LEVEL() . " : " . $cs->level
                    . " " . ($upg && $myself ? new \Quantyl\XML\Html\A("/Game/LevelUp/Skill?cs={$cs->id}&pt={$cst}", \I18n::UPGRADE()) : "")
                    . " " . new \Quantyl\XML\Html\Meter(0, $max, $val)
                    . "</div>" ;
        
        $msg .= "<div class=\"uses\">" 
                    . \I18n::USES() . " : " . $cs->uses
                    . "</div>" ;
        
                    
        $msg .= "<div class=\"links\">" ;
        if ($myself) {
            $msg .= $this->getInstances($cs) ;
        }
        $msg .= "</div>" ;
        $msg .= "</div>" ;
        
        $msg .= "</div>" ; // skillCard

        
        return $msg ;
        
    }
    
    
    private function getInstances(\Model\Game\Skill\Character $cs) {
        $j = $cs->skill->building_job ;
        $canenter = $cs->character->position->canEnter($cs->character) ;
        
        if ($j != null && $j->equals(\Model\Game\Building\Job::GetByName("OutSide"))) {
            return new \Quantyl\XML\Html\A("/Game/Building/OutSide?city={$cs->character->position->id}", \I18n::SEE_BUILDING()) ;
        } else if ($canenter && $cs->level > 0) {
            $inst = array() ;
            if ($j == null) {
                return "-" ;
            }
            foreach (\Model\Game\Building\Instance::GetFromCityAndJob($cs->character->position, $j) as $i) {
                $inst[] = new \Quantyl\XML\Html\A("/Game/Building?id={$i->id}", \I18n::SEE_BUILDING()) ;
            }
            if (count($inst) == 0) {
                return \I18n::NONE() ;
            } else {
                return implode(", ", $inst) ;
            }
            
        } else {
            return "-" ;
        }
    }
    
}
