<?php

namespace Services\Game\Inventory\Learn ;

abstract class Base extends \Services\Base\Character {
    
    private $_cs ;
    
    public function getCs() {
        if (! isset($this->_cs)) {
            $learn = \Model\Game\Skill\Learn::GetFromCharacteristic($this->getCharacteristic()) ;
            $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($this->getCharacter(), $learn->skill) ;
            $this->_cs = $cs ;
        }
        return $this->_cs ;
    }
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("inventory", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Inventory::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Own inventory
        if (! $this->inventory->character->equals($this->getCharacter())) {
           throw new \Quantyl\Exception\Http\ClientForbidden() ; 
        }

    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $cs = $this->getCs() ;
        
        $form->setMessage(\I18n::LEARN_INVENTORY($cs->skill->getName(), $cs->getTimeCost())) ;
        
    }
    
    public function getComplete() {
        return new \Model\Game\Tax\Complete(0, 0) ;
    }
    
    public function onProceed($data) {
        
        $char       = $this->getCharacter() ;
        $cs         = $this->getCs() ;
        $tool       = $data["tool"] ;
        $timecost   = $cs->getTimeCost() / $this->getCoef($tool, $cs) ;
        
        // Check time
        if ($char->getTime() < $timecost) {
            throw new \Exception(\I18n::EXP_NOT_ENOUGH_TIME()) ;
        } else if ($tool != null && $tool->amount < 0.01) {
            throw new \Exception(\I18n::EXP_SKILL_TOOL_BELOW()) ;
        }
        
        $msg = "" ;
        
        // Learning
        $msg .= $this->proceedLearning() ;
        
        // experience
        $char->addTime(-$timecost) ;
        $msg .= $char->addExperienceBySkill($timecost, $cs) ;
        
        $cs->addUse() ;
        
        $char->update() ;
        $cs->update() ;
        
        // Tool
        if ($tool != null) {
            $tool->amount -= 0.01 ;
            $tool->update() ;
        }
        
        $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
        
    }
    
    public function getCoef($inv, $cs) {
        if ($inv == null) {
            return $cs->skill->by_hand ;
        } else {
            $tool = \Model\Game\Skill\Tool::GetFromSkillAndItem($cs->skill, $inv->item) ;
            return $tool->coef ;
        }
    }
    
    public abstract function getCharacteristic() ;
    
    public abstract function proceedLearning() ;
}
