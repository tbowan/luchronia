<?php

namespace Services\Game\Ministry\Building\TradingPost ;

class Buy extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("nation", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Nation::getBddTable())) ;
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Must be a trading post
        $job = \Model\Game\Building\Job::GetByName("TradingPost") ;
        if (! $job->equals($this->instance->job)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::EXP_TRADINGPOST_ISNOT()) ;
        }
        
        // Check nation is the one of the city
        $capital = $this->instance->city->palace ;
        $nation = $this->nation->nation ;
        if ($capital != null && (! $capital->equals($nation) || $nation != null)) {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::EXP_TRADINGPOST_NATIONS_DONT_MATCH()) ;
        }
        
        // Check this order is a toSell one
        if (! $this->nation->type->equals(\Model\Game\Trading\Type::ToBuy())) {
            // TODO : message
            throw new \Quantyl\Exception\Http\ClientBadRequest() ;
        }
    }
    
    public function getCity() {
        return $this->instance->city ;
    }

    public function getMinistry() {
        return $this->instance->job->ministry ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::MINISTER_BUY_TRADING_POST_MESSAGE()) ;
        // Sell Part
        $sell = $form->addInput("sell", new \Quantyl\Form\FieldSet(\I18n::BUY())) ;
        $sell->addMessage(\I18n::MINISTER_BUY_TRADING_POST_ORDER_MESSAGE(
                ($this->nation->nation == null ? "-" : $this->nation->nation->getName()),
                $this->nation->item->getName(),
                ($this->nation->amount === null ? "&#8734;" : $this->nation->amount),
                $this->nation->price
                )) ;
        $sell->addInput("quantity", new \Quantyl\Form\Fields\Integer(\I18n::QUANTITY(), true)) ;
        // Stock part
        $stock = $form->addInput("stock", new \Quantyl\Form\FieldSet(\I18n::STOCK())) ;
        $stock->addInput("instance", new \Form\InstanceStock($this->nation->item, $this->getCity(), \I18n::BUILDING())) ;
    }
    
    public function onProceed($data) {
        $city = $this->getCity() ;
        
        $max = $city->credits / $this->nation->price ;
        if ($this->nation->amount !== null) {
            $max = max($max, $this->nation->amount ) ;
        } else if ($this->nation->nation !== null) {
            $max = max($max, \Model\Game\Ressource\City::GetAmount($this->nation->nation, $this->nation->ressource) );
        }
        
        
        if ($data["sell"]["quantity"] <= 0) {
            throw new \Exception(\I18n::EXP_QUANTITY_MUST_BE_POSITIVE()) ;
        } else if ($data["sell"]["quantity"] > $max) {
            throw new \Exception(\I18n::EXP_QUANTITY_TOO_HIGH($max)) ;
        }
        
        $credits = $data["sell"]["quantity"] * $this->nation->price ;
        
        if ($city->credits < $credits ) {
            throw new \Exception(\I18n::EXP_DONT_HAVE_ENOUGH_MONEY($credits)) ;
        }
        
        // Pay :
        $city->addCredits(- $credits) ;
        
        if ($this->nation->nation != null) {
            $this->nation->nation->addCredits($credits) ;
        }
        
        // Get items
        $stock = \Model\Game\Ressource\City::createFromValues(array(
            "city" => $this->getCity(),
            "item" => $this->nation->item,
            "amount" => $data["sell"]["quantity"],
            "instance" => $data["stock"]["instance"],
            "price" => null,
            "published" => false
        )) ;
        
        // Remove from sell
        if ($this->nation->amount !== null) {
            $this->nation->amount -= $data["sell"]["quantity"] ;
            $this->nation->update() ;
        } else if ($this->nation->nation !== null) {
            \Model\Game\Ressource\City::DelItem($this->nation->nation, $this->nation->item, $data["sell"]["quantity"] ) ;
        }
        
    }

}
