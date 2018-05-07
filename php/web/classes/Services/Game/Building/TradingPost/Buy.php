<?php

namespace Services\Game\Building\TradingPost ;

class Buy extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("order", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Tradingpost::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Check this order is a toSell one
         if (! $this->order->trading->type->equals(\Model\Game\Trading\Type::ToBuy())) {
            // TODO : message
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
        
    }
    
    public function getMessage() {
        $nation = $this->order->trading ;
        $item   = $nation->item ;
        
        $tp     = \Model\Game\Building\Tradingpost::GetFromInstance($this->order->instance) ;
        
        $price  = $nation->price * (1 + $tp->tax) ;
                
        $res  = "<ul>" ;
        $res .= "<li><strong>" . \I18n::ITEM() . " : </strong>" . $item->getImage("icone") . " " . $item->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::PRICE() . " : </strong>" . $price . "</li>" ;
        $res .= "<li><strong>" . \I18n::AMOUNT_FOR_CREDITS() . " : </strong>" . number_format($this->getCharacter()->getCredits() / $price, 2) . "</li>" ;
        $res .= "<li><strong>" . \I18n::AMOUNT_INVENTORY_AVAILABLE() . " : </strong>" . \Model\Game\Ressource\Inventory::GetAvaiableSpace($this->getCharacter(), $item) . "</li>" ;
        $res .= "<li><strong>" . \I18n::AMOUNT_ORDER() . " : </strong>" . ($nation->amount === null ? "&#8734;" : $nation->amount) . "</li>" ;
        
        $palace_city = $this->order->trading->nation ;
        if ($palace_city !== null && $nation->amount === null) {
            $res .= "<li><strong>" . \I18n::AMOUNT_PALACE()     . " : </strong>" . \Model\Game\Ressource\City::GetAmount($palace_city, $item) . "</li>" ;
        }
        
        $res .= "</ul>" ;
        
        return $res;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage($this->getMessage()) ;
        $form->addInput("qtty", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT())) ;
    }
    
    
    public function onProceed($data) {
        
        $item   = $this->order->trading->item ;
        $tp     = \Model\Game\Building\Tradingpost::GetFromInstance($this->order->instance) ;
        $nation = $this->order->trading ;
        $character = $this->getCharacter() ;
        
        $price  = $nation->price * (1 + $tp->tax) ;
        
        $max = $character->getCredits() / $price ;
        $max = min($max, \Model\Game\Ressource\Inventory::GetAvaiableSpace($character, $item)) ;
        
        $palace_city = $this->order->trading->nation ;
        if ($palace_city !== null && $nation->amount === null) {
            $max = min($max, \Model\Game\Ressource\City::GetAmount($palace_city, $item)) ;
        }
        
        if ($nation->amount !== null) {
            $max = min($max, $nation->amount) ;
        }
        
        // Check amount
        if ($data["qtty"] <= 0) {
            throw new \Exception(\I18n::EXP_BUY_NEGATIVE());
        } else if ($data["qtty"] > $max ) {
            throw new \Exception(\I18n::EXP_BUY_EXCEED($max));
        }
        
        
        // Remove from character
        
        $total = $price * $data["qtty"] ;
        
        $gain = $nation->price * $data["qtty"] ;
        $tax  = $total - $gain ;
        
        // Character
        $rest = \Model\Game\Ressource\Inventory::AddItem($this->getCharacter(), $item, $data["qtty"]) ;
        if ($rest > 0) {
            $me = $this->getCharacter() ;
            $city = $this->order->instance->city ;
            \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
            $msg = \I18n::INVENTORY_FULL_GIVE_CITY(
                    $rest, $item->getName(),
                    $city->id, $city->id, $city->getName()
                    ) ;
        }
                
        $character->addCredits( - $total) ;
        $character->update() ;
        
        // capital
        if ($palace_city != null) {
            $palace_city->addCredits($gain) ;
            if ($nation->amount === null) {
                $remain = \Model\Game\Ressource\City::DelItem($palace_city, $item, $data["qtty"]) ;
            }
        }
        
        // City
        $tp->instance->city->addCredits($tax) ;
        
        // Order
        if ($nation->amount !== null) {
            $this->order->trading->removeAmount($data["qtty"]) ;
            $this->order->trading->update() ;
        }
        
        if ($msg != null) {
            $this->setAnswer(new \Quantyl\Answer\Message($msg)) ;
        }
    }
}
