<?php

namespace Services\Game\Building\TradingPost ;

class Sell extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("order", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Tradingpost::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Check this order is a toSell one
        if (! $this->order->trading->type->equals(\Model\Game\Trading\Type::ToSell())) {
            // TODO : message
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
        
    }
    
    public function getMessage() {
        $nation = $this->order->trading ;
        $item   = $nation->item ;
        
        $tp     = \Model\Game\Building\Tradingpost::GetFromInstance($this->order->instance) ;
        
        $gain  = $nation->price * (1 - $tp->tax) ;
                
        $res  = "<ul>" ;
        $res .= "<li><strong>" . \I18n::ITEM() . " : </strong>" . $item->getImage("icone") . " " . $item->getName() . "</li>" ;
        $res .= "<li><strong>" . \I18n::PRICE() . " : </strong>" . $gain . "</li>" ;
        $res .= "<li><strong>" . \I18n::AMOUNT_INVENTORY() . " : </strong>" . \Model\Game\Ressource\Inventory::GetAmount($this->getCharacter(), $item) . "</li>" ;
        
        $palace_city = $this->order->trading->nation ;
        if ($palace_city !== null) {
            $res .= "<li><strong>" . \I18n::AMOUNT_PALACE_AVAILABLE()     . " : </strong>" . \Model\Game\Ressource\City::GetAvaiableSpace($palace_city, $item) . "</li>" ;
        }
        
        $res .= "<li><strong>" . \I18n::AMOUNT_ORDER() . " : </strong>" . ($nation->amount === null ? "&#8734;" : $nation->amount) . "</li>" ;
        $res .= "</ul>" ;
        
        return $res;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage($this->getMessage()) ;
        $form->addInput("qtty", new \Quantyl\Form\Fields\Float(\I18n::AMOUNT())) ;
    }
    
    
    public function onProceed($data) {
        
        $item = $this->order->trading->item ;
        $tp   = \Model\Game\Building\Tradingpost::GetFromInstance($this->order->instance) ;
        
        $max = \Model\Game\Ressource\Inventory::GetAmount($this->getCharacter(), $item) ;
        $palace_city = $this->order->trading->nation ;
        if ($palace_city !== null) {
            $max = min($max, \Model\Game\Ressource\City::GetAvaiableSpace($palace_city, $item)) ;
        }
        
        $remain = $this->order->trading->amount ;
        if ($remain !== null) {
            $max = min($max, $remain) ;
        }
        
        // Check amount
        if ($data["qtty"] <= 0) {
            throw new \Exception(\I18n::EXP_SELL_NEGATIVE());
        } else if ($data["qtty"] > $max ) {
            throw new \Exception(\I18n::EXP_SELL_EXCEED($max));
        }
        
        
        // Remove from character
        
        $price = $this->order->trading->price ;
        $total = $price * $data["qtty"] ;
        
        $gain = $total * (1 - $tp->tax) ;
        $tax = $total * $tp->tax ;
        
        // Character
        $character = $this->getCharacter() ;
        $remain = \Model\Game\Ressource\Inventory::DelItem($this->getCharacter(), $item, $data["qtty"]) ;
        $character->addCredits($gain) ;
        $character->update() ;
        
        // capital
        if ($palace_city != null) {
            $remain = \Model\Game\Ressource\City::AddInsideItem($palace_city, $item, $data["qtty"]) ;
        }
        
        // City
        $tp->instance->city->addCredits($tax) ;
        
        // Remove for order
        $this->order->trading->removeAmount($data["qtty"]) ;
        $this->order->trading->update() ;
    }
    
}
