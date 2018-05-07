<?php

namespace Answer\Widget\Game\Character;

class JobCard extends \Answer\Widget\Misc\Card{
    private $_character ;
    private $_metier;
    private $_canenter ; 
    
    public function __construct(\Model\Game\Character $c, \Model\Game\Skill\Metier $m) {
        parent::__construct();
        $this->_character = $c ;
        $this->_metier = $m;
        $this->_canenter  = $this->_character->position->canEnter($this->_character) ;
    }
    
    private function getInstances(\Model\Game\Skill\Character $cs) {
        $j = $cs->skill->building_job ;
        
        if ($j != null && $j->equals(\Model\Game\Building\Job::GetByName("OutSide"))) {
            return new \Quantyl\XML\Html\A("/Game/Building/OutSide?city={$cs->character->position->id}", \I18n::SEE_BUILDING()) ;
        } else if ($this->_canenter && $cs->level > 0) {
            $inst = array() ;
            if ($j == null) {
                return "-" ;
            }
            foreach (\Model\Game\Building\Instance::GetFromCityAndJob($this->_character->position, $j) as $i) {
                $inst[] = new \Quantyl\XML\Html\A("/Game/Building?id={$i->id}", \I18n::SEE_BUILDING()) ;
            }
            if (count($inst) == 0) {
                return \I18n::NONE() ;
            } else {
                return implode("<br/>", $inst) ;
            }
            
        } else {
            return "-" ;
        }
    }
    
    public function getBody() {
        
        $res = "" ;
        
        foreach (\Model\Game\Skill\Skill::getFromMetier($this->_metier) as $skill) {
            $cs =\Model\Game\Skill\Character::GetFromCharacterAndSkill($this->_character, $skill);
            if(($cs != null )){
                $res .= "<h4> " . $skill->getName() . " " . \I18n::HELP("/Help/Skill?id={$skill->id}") . "</h4>";              
                $res .= "<ul>";
                $res .= "<li>" . \I18n::LEVEL() . " " . $cs->level . "</li>";
                $res .= "<li>" . \I18n::LEARNING(). " " . $cs->learning . " / " . $cs->getLearningThreshold(). "</li>";
                $res .= "<li>" . \I18n::USES(). " " . $cs->uses . "</li>";
                $res .= "<li>" . \I18n::BUILDING(). " " . $this->getInstances($cs). "</li>";        
                $res .= "</ul>";
            }

        }
        
        return $res ;
    }

    public function getHead() {
        return $this->_metier->getName();
    }

    public function getClasses() {
        return parent::getClasses() . " notice";
    }   
}
