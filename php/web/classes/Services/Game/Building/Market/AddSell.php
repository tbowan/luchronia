<?php

namespace Services\Game\Building\Market ;
    
class AddSell extends \Services\Base\Character {
    
    private $_tax ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
    }
    
    public function init() {
        parent::init();
        $this->_tax = \Model\Game\Tax\Tradable::GetFromInstance($this->instance) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Check position
        $me = $this->getCharacter() ;
        if (! $me->position->equals($this->instance->city)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_BE_SAME_POSITION()) ;
        }
        
        // Check max orders
        $used = \Model\Game\Trading\Character\Market\Sell::GetCountMarketAndCharacter($this->instance, $me) ;
        $l = $this->instance->level ;
        $available = $l * ($l + 1) / 2 ;
        if ($used >= $available) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_MARKET_MAX_ORDER_ALLOWED()) ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::MARKET_ADDSELL_MESSAGE()) ;
        
        $form->addInput("ressource", new \Form\Inventory($this->getCharacter(), \I18n::RESSOURCE(), true)) ;
        $form->addInput("quantity", new \Quantyl\Form\Fields\Integer(\I18n::QUANTITY())) ;
        $form->addInput("price", new \Quantyl\Form\Fields\Float(\I18n::PRICE())) ;
        
    }
    
    public function onProceed($data) {
        
        $char      = $this->getCharacter() ;
        $item      = $data["ressource"]->item ;
        $available = \Model\Game\Ressource\Inventory::GetAmount($char, $item) ;
        
        // Check 1 - Quantity
        
        if ($data["quantity"] <= 0) {
            throw new \Exception(\I18n::EXP_QUANTITY_MUST_BE_POSITIVE()) ;
        } else if ($data["quantity"] > $available) {
            throw new \Exception(\I18n::EXP_DONT_HAVE_ENOUGH_TO_SELL()) ;
        }
        
        // Check 2 - price is positive
        if ($data["price"] <= 0) {
            throw new \Exception(\I18n::EXP_PRICE_MUST_BE_POSITIVE()) ;
        }
        
        // Check 3 - Afford Taxes
        
        $tax = $data["quantity"] * $data["price"] * $this->_tax->order ;
        if ($char->getCredits() < $tax) {
            throw new \Exception(\I18n::EXP_DONT_HAVE_ENOUGH_MONEY_PAY_TAX($tax)) ;
        }
        
        // Do it
        
        \Model\Game\Trading\Character\Market\Sell::createFromValues(array(
                "character" => $char,
                "market" => $this->instance,
                "ressource" => $item,
                "total" => $data["quantity"],
                "remain" => $data["quantity"],
                "price" => $data["price"]
            )) ;
        
        \Model\Game\Ressource\Inventory::DelItem($char, $item, $data["quantity"]) ;
        $char->addCredits( - $tax) ;
        $this->instance->city->addCredits($tax) ;
        $char->update() ;
        
    }
    
}
