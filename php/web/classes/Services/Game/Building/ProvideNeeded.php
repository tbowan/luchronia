<?php

namespace Services\Game\Building ;

class ProvideNeeded extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("need", new \Quantyl\Form\Model\Id(\Model\Game\Building\Need::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage($this->getMessage()) ;
        $form->addInput("qtty", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT())) ;
    }
    
    public function getMessage() {
        $item   = $this->need->item ;
        
        $res  = \I18n::PROVIDE_NEEDED_MESSAGE() ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::ITEM() . " : </strong>" . $item->getImage("icone") . " " . $item->getName() . "</li>" ;
        
        $city = $this->need->site->instance->city ;
        $price = \Model\Game\Trading\Needed::GetPrice($item, $city) ;
        $res .= "<li><strong>" . \I18n::PRICE() . " : </strong>" .  $price . "</li>" ;
        
        if ($price == 0) {
            $afordable = $this->need->getRemain() ;
        } else {
            $afordable = min($this->need->getRemain(), round($city->credits / $price, 2)) ;
        }
        
        $res .= "<li><strong>" . \I18n::AMOUNT_AFORDABLE() . " : </strong>" . $afordable . "</li>" ;
        $res .= "<li><strong>" . \I18n::AMOUNT_NEEDED() . " : </strong>" . $this->need->getRemain() . "</li>" ;
        $res .= "<li><strong>" . \I18n::AMOUNT_AVAIlABLE() . " : </strong>" . \Model\Game\Ressource\Inventory::GetAmount($this->getCharacter(), $item) . "</li>" ;
        $res .= "</ul>" ;
        
        return $res;
    }
    
    public function onProceed($data) {
        $need           = $this->need ;
        $item           = $need->item ;
        $neededamout    = $need->getRemain() ;
        $maxamount      = \Model\Game\Ressource\Inventory::GetAmount($this->getCharacter(), $item) ;
        $amount         = $data["qtty"] ;
        $max            = min($neededamout, $maxamount) ;
        
        $char           = $this->getCharacter() ;
        $city           = $char->position ;
        $price          = \Model\Game\Trading\Needed::GetPrice($item, $city) ;
        $payement       = min($price * $amount, $city->credits) ;
        
        
        // Check amount
        if ($amount <= 0) {
            throw new \Exception(\I18n::EXP_PROVIDE_NEGATIVE());
        } else if ($amount > $max) {
            throw new \Exception(\I18n::EXP_PROVIDE_EXCEED($max));
        }
        
        \Model\Game\Ressource\Inventory::DelItem($this->getCharacter(), $item, $amount) ;
        $char->addCredits($payement) ;
        $char->update() ;
        
        $city->credits -= $payement ;
        $city->update() ;

        $need->provided += $amount ;
        $need->update() ;
        
        $this->setAnswer(new \Quantyl\Answer\Message(
                \I18n::BUILDING_NEED_REMAIN(
                        $amount,
                        $item->getName(),
                        $payement,
                        $neededamout - $amount
                        ))) ;
    }
    
}
