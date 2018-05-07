<?php

namespace Widget\Game\Character ;

class LevelUp extends \Quantyl\Answer\Widget {
    
    private $_char ;
    
    public function __construct(\Model\Game\Character $c) {
        $this->_char = $c ;
    }
    
    public function getPoints() {
        $res = "<h2>" . \I18n::LEVEL_POINTS() . "</h2>" ;
        $res .= \I18n::LEVEL_POINT_HELP() ;
        $res .= \I18n::YOU_HAVE_POINTS($this->_char->point) ;
        
        return $res ;
    }
    
    public function getSkillsKnown() {
        $res = "" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::SKILL(),
            \I18n::LEVEL(),
            \I18n::LEARNING(),
            \I18n::USES(),
            \I18n::ACTIONS()
        )) ;
        foreach (\Model\Game\Skill\Character::GetFromCharacter($this->_char) as $cs) {
            $skill = $cs->skill ;

            $cost = ceil(($cs->getLearningThreshold() - $cs->learning) / 100.0) ;
            $upgradable = $cs->level >= 0 ;

            $table->addRow(array(
                $skill->getImage("icone") . " " . $skill->getName() . " " . \I18n::HELP("/Help/Skill?id={$cs->skill->id}"),
                $cs->level,
                $cs->learning . " / " . $cs->getLearningThreshold(),
                $cs->uses ,
                ($upgradable ? new \Quantyl\XML\Html\A("/Game/LevelUp/Skill?cs={$cs->id}&pt={$cost}", \I18n::SKILL_INCREASE($cost)) : "")
            )) ;
        }
        $res .= $table ;
        return $res ; 
    }
    
    public function getCharacteristics() {
        $res = "<h2>" . \I18n::LEVEL_CHARACTERISTICS() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::CHARACTERISTIC(),
            \I18n::CURRENT_VALUE(),
            \I18n::ACTIONS()
        )) ;
        foreach (\Model\Game\Characteristic::GetAll() as $carac) {
            $cc = \Model\Game\Characteristic\Character::GetFromCharacterAndCharacteristic($this->_char, $carac) ;
            $v = ($cc == null ? 0 : $cc->value) ;
            $table->addRow(array(
                $carac->getImage("icone-med") . " " . $carac->getName() . " " . \I18n::HELP("/Help/Characteristic?id={$carac->id}"),
                $v,
                new \Quantyl\XML\Html\A("/Game/LevelUp/Characteristic?characteristic={$carac->id}", \I18n::CHARACTERISTIC_INCREASE(1))
            )) ;
        }
        
        $res .= $table ;
        return $res ;
    }
    
    public function getSkills() {
        $res  = "<h2>" . \I18n::LEVEL_SKILLS() . "</h2>" ;
        $res .= new \Quantyl\XML\Html\A("/Game/LevelUp/BuySkill", \I18n::BUY_SKILL()) ;
        $res .= $this->getSkillsKnown() ;
        
        return $res ;
    }
    
    public function getSlots() {
        $res = "<h2>" . \I18n::LEVEL_SLOTS() . "</h2>" ;
        $current = $this->_char->inventory ;
        $res .= \I18n::LEVEL_SLOTS_CURRENT($current, $current - 4) ;
        $res .= new \Quantyl\XML\Html\A("/Game/LevelUp/Slot", \I18n::INCREASE()) ;
        return $res ;
    }
    
    public function getContent() {

        return ""
                . $this->getPoints()
                . $this->getSkills()
                . $this->getCharacteristics()
                . $this->getSlots() ;
        
    }
    
}
