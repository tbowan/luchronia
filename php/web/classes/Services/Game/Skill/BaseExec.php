<?php

namespace Services\Game\Skill ;

abstract class BaseExec extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("cs", new \Quantyl\Form\Model\Id(\Model\Game\Skill\Character::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        
        if (! $me->equals($this->cs->character)) {
            throw new \Quantyl\Exception\Http\ClientUnauthorized() ;
        } else if (! $me->position->equals($this->getCity())) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        } else if ($this->cs->level <= 0) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_SKILL_LEVEL_BELOW()) ;
        } else if ($me->health <= 0) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NO_HEALTH()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setRendered(new \Form\Renderer\TwoSide()) ;
        $form->setMessage($this->getMessage()) ;
        $form->addInput("tool", $this->getToolInput()) ;
    }
    
    public function getMessage() {
        $info = "<h2>" . \I18n::DESCRIPTION() . "</h2>" ;
        $info .= "<div class=\"card-1-4\">" . $this->cs->getImage("left") . "</div>" ;
        $info .= "<div class=\"card-3-4\">" . $this->cs->skill->getDescription() . "</div>" ;
        
        
        $info .= "<h2>" . \I18n::INFORMATIONS() . "</h2>" ;
        $info .= "<ul class=\"itemList\">" ;
        $info .= $this->getSkillInfo() ;
        $info .= $this->getMetierInfo() ;
        $info .= $this->getBuildingInfo() ;
        $info .= $this->getCityInfo() ;
        $info .= "</ul>" ;
        
        return $info ;
    }
    
    public function getCityInfo() {
        $city = $this->getCity() ;
        $res  = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\"></div>" ;
        $res .= "<div class=\"description\">" ;
        $res .= "<div class=\"name\">" . \I18n::CITY() . " : " . new \Quantyl\XML\Html\A("/Game/City?id={$city->id}", $city->getName()) . "</div>" ;
        $res .= "<div>" . $city->getGeoCoord() . "</div>" ;
        $res .= "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }

    public function getBuildingInfo() {
        return "" ;
    }
    
    public function getSkillInfo() {
        
        $skill = $this->cs->skill ;
        
        $res  = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $skill->getImage() . "</div>" ;
        $res .= "<div class=\"description\">" ;
        $res .= "<div class=\"name\">" . \I18n::SKILL() . " : " . new \Quantyl\XML\Html\A("/Help/Skill?id={$skill->id}", $skill->getName()) . "</div>" ;
        $res .= "<div>" . \I18n::LEVEL() . " : " . $this->cs->level . "</div>" ;
        $res .= "<div>" . \I18n::LEARNING() . " : " . $this->cs->learning . " / " . $this->cs->getLearningThreshold() . "</div>" ;
        $res .= "<div>" . \I18n::USES() . " : " . $this->cs->uses . "</div>" ;
        $res .= "</div>" ;
        $res .= "</div></li>" ;

        return $res ;
    }
    
    public function getMetierInfo() {
        
        $metier = $this->cs->skill->metier ;
        $char   = $this->cs->character ;
        $uses   = $metier->getUse($char) ;
        $level  = $metier->getLevel($char, $uses) ;
        $medal  = $metier->getMedal($char, $uses) ;
        
        $res  = "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $medal->getImage($metier) . "</div>" ;
        $res .= "<div class=\"description\">" ;
        $res .= "<div class=\"name\">" . \I18n::METIER() . " : " . new \Quantyl\XML\Html\A("/Help/Metier?id={$metier->id}", $metier->getName()) . "</div>" ;
        $res .= "<div>" . \I18n::LEVEL() . " : " . $level . ", " . $medal->getName() . "</div>" ;
        $res .= "<div>" . \I18n::USES() . " : " . $uses . " / " . $metier->getThreshold($level+1) . "</div>" ;
        $res .= "</div>" ;
        $res .= "</div></li>" ;

        return $res ;
    }
    
    
    public function getTime($tool) {
        $base = $this->cs->getTimeCost() ;
        $coef = ($tool == null ? $this->cs->skill->by_hand : $tool->getCoef()) ;
        return $base / $coef ;
    }
    
    public function getAmount($munition) {
        return ($munition == null ? 1.0 : 1.0 + $munition->getCoef()) ;
    }
    
    public function getTaxableAmount($munition) {
        return $this->getAmount($munition) ;
    }
    
    public function getComplete() {
        return \Model\Game\Tax\Complete::Factory(
                $this->cs->character,
                $this->cs->skill,
                $this->getJob(),
                $this->getCity()
                ) ;
    }
    
    public function getTax($amount) {
        $tax = $this->getComplete() ;
        $min = - $this->getCity()->credits ;
        return max($min, $tax->getTax($amount)) ;
    }

    public function onProceed($data, $form) {
        $msg                                = "" ;
        $char                               = $this->getCharacter() ;
        $city                               = $this->getCity() ;
        list($i1, $i2, $tool, $munition)    = $data["tool"] ;

        $time                               = $this->getTime($tool) ;
        $amount                             = $this->getAmount($munition) ;
        $taxable                            = $this->getTaxableAmount($munition) ;
        $credits                            = $this->getTax($taxable) ;

        // 1. Checks
        
        if ($char->getTime() < $time) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_TIME()) ;
        } else if ($char->getCredits() < $credits) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_CREDITS($credits, $char->getCredits())) ;
        }
        
        $msg .= $this->doStuff($amount, $data) ;
        
        // Character
        $char->addCredits(- $credits) ;
        $char->addTime(-$time) ;
        $msg .= $char->addExperienceBySkill($time, $this->cs) ;
        $char->update() ;
        
        // Skill
        $metier = $this->cs->skill->metier ;
        $level_before = $metier->getLevel($char) ;
        
        $this->cs->addUse() ;
        $this->cs->update() ;
        
        $level_after = $metier->getLevel($char) ;
        
        if ($level_before != $level_after) {
            $msg .= \I18n::CONGRATULATION_METIER_LEVEL($level_after, $metier->id, $metier->getName()) ;
            $medal_before = \Model\Game\Skill\Medal::FactoryLevel($level_before) ;
            $medal_after = \Model\Game\Skill\Medal::FactoryLevel($level_after) ;
            if (! $medal_after->equals($medal_before)) {
                $msg .= \I18n::CONGRATULATION_METIER_MEDAL($medal_after->getImage($metier, "icone-inline"), $medal_after->getName()) ;
            }
            
        }
        
        // Tool
        if ($i1 != null) {
            $i1->amount -= 0.01 ;
            $i1->update() ;
        }
        
        if ($i2 != null) {
            $i2->amount -= $munition->amount ;
            $i2->update() ;
        }
        
        // The city (credits only)
        $city->addCredits($credits) ;
        
        $form->setMessage($this->getMessage()) ;
        $msg .= $form ;
        
        $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
        
    }
    
    public function getTitle() {
        return $this->cs->skill->getName(); 
    }
    
    public function getToolInput() {
        return new \Form\Tool\Base($this->cs, $this->getComplete()) ;
    }

    public abstract function getCity() ;
    
    public abstract function getJob() ;
        
    public abstract function doStuff($amount, $data) ;
    
}
