<?php

namespace Services\Game\Building\TradingSkill ;

class Close extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sell", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Skill::getBddTable())) ;
    }

    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->sell->character->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CANNOT_CLOSE_OTHER_ORDER()) ;
        }
    }
    
    public function getCity() {
        return $this->sell->instance->city ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::CLOSE_SELL_SKILL_MESSAGE(
                $this->sell->skill->getName(),
                $this->sell->remain,
                $this->sell->remain * $this->sell->time,
                ($this->sell->total - $this->sell->remain) * $this->sell->price,
                ($this->sell->total - $this->sell->remain) * $this->sell->time
                )) ;
    }
    
    public function onProceed($data) {
        
        $sold       = $this->sell->total - $this->sell->remain ;
        $exp        = $sold * $this->sell->time ;
        $credits    = $sold * $this->sell->price ;
        $time       = $this->sell->remain * $this->sell->time ;
        
        $me = $this->getCharacter() ;
        $me->addTime($time) ;
        $me->addCredits($credits) ;
        $me->addExperience($exp) ;
        $me->update() ;
        
        $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($me, $this->sell->skill) ;
        $cs->addUse(floor($this->sell->sold * $this->sell->use)) ;
        $cs->update() ;
        
        $this->sell->delete() ;

    }

}
