<?php

namespace Answer\View\Help ;

class Metier extends \Answer\View\Base {
    
    private $_metier ;
    private $_viewer ;
    
    public function __construct($service, \Model\Game\Skill\Metier $m, $v) {
        parent::__construct($service) ;
        $this->_metier  = $m ;
        $this->_viewer  = $v ;
    }
    
    public function getDescription($classes = "") {
        $res = "<div class=\"card-1-3\">" ;
        $res .= $this->_metier->getMedalImg($this->_viewer, "left-illustr") ;
        $res .= "</div>" ;
        $res .= "<div class=\"card-1-3\">" ;
        $res .= $this->_metier->getDescription() ;
        $res .= "</div>" ;

        return new \Answer\Widget\Misc\Section(\I18n::DESCRIPTION(), new \Quantyl\XML\Html\A("/Help/Metier", \I18n::HELP_SEE_METIERS()), "", $res, $classes) ;
    }
    
    public function getSkills($classes = "", $itemclass = "") {
        $res = "" ;
        $res .= "<ul class=\"itemList\">" ;
        foreach (\Model\Game\Skill\Skill::getFromMetier($this->_metier) as $sk) {
            $res .= new \Answer\Widget\Help\Skill\SkillAsItem($sk, $this->_viewer, $itemclass) ;
        }
        $res .= "</ul>" ;
        
        return new \Answer\Widget\Misc\Section(\I18n::SKILL_LIST(), "", "", $res, $classes) ;
    }
    
    
    public function getCharacter($classes  ="") {
        $char = $this->_viewer ;
        if ($char !== null) {
            $cur_uses  = $this->_metier->getUse($char) ;
            $cur_medal = $this->_metier->getMedal($char, $cur_uses) ;
            $cur_level = $this->_metier->getLevel($char, $cur_uses) ;
        } else {
            $cur_level = 0 ;
        }
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::MEDAL(),
            \I18n::LEVEL(),
            \I18n::USES(),
            \I18n::TIME()
        )) ;
        
        foreach (\Model\Game\Skill\Medal::GetAll() as $id => $medal) {
            if ($cur_level != null && $id > $cur_level) {
                $row = $table->addRow(array(
                    $cur_medal->getImage($this->_metier, "icone-inline") . " " . $char->getName(),
                    $cur_level,
                    $cur_uses . " / " . \Model\Game\Skill\Metier::getThreshold($cur_level + 1),
                    \Model\Game\Skill\Character::getTimeNeeded($cur_level)
                )) ;
                $row->setAttribute("class", "unread") ;
                $cur_level = null ;
            }
            $table->addRow(array(
                $medal->getImage($this->_metier, "icone-inline") . " " . $medal->getName(),
                $id,
                \Model\Game\Skill\Metier::getThreshold($id + 1),
                \Model\Game\Skill\Character::getTimeNeeded($id)
            )) ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::LEVEL(), "", "", "$table", $classes) ;
    }
    
    public function getTplContent() {
        return ""
                . $this->getDescription("card-1-2")
                . $this->getCharacter("card-1-2")
                . $this->getSkills("", "card-1-3")
                ;
    }

}
