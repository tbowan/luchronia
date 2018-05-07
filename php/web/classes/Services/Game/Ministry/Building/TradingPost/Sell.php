<?php

namespace Services\Game\Ministry\Building\TradingPost ;

class Sell extends \Services\Base\Minister {
    
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
        if (! $this->nation->type->equals(\Model\Game\Trading\Type::ToSell())) {
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
        
        $form->addMessage(\I18n::MINISTER_SELL_TRADING_POST_MESSAGE()) ;
        // Sell Part
        $sell = $form->addInput("sell", new \Quantyl\Form\FieldSet(\I18n::SELL())) ;
        $sell->addMessage(\I18n::MINISTER_SELL_TRADING_POST_ORDER_MESSAGE(
                ($this->nation->nation == null ? "-" : $this->nation->nation->getName()),
                $this->nation->item->getName(),
                ($this->nation->amount === null ? "&#8734;" : $this->nation->amount),
                $this->nation->price
                )) ;
        $sell->addInput("stock", new \Form\CityStock($this->nation->item, $this->getCity(), \I18n::STOCK(), true)) ;
                
        // Stock part
    }
    
    public function onProceedFixed($data) {
        $stock = $data["sell"]["stock"] ;
        $qtty = min($stock->amount, $this->nation->amount) ;
        $credits = $qtty * $this->nation->price ;
        
        $stock->city->addCredits($credits) ;
        $stock->amount -= $qtty ;
        $stock->update() ;
        
        $this->nation->amount -= $qtty ;
        $this->nation->update() ;
    }
    
    public function onProceedInfinite($data) {
        $stock = $data["sell"]["stock"] ;
        $qtty = $stock->amount ;
        $credits = $qtty * $this->nation->price ;
        
        $stock->city->addCredits($credits) ;
        $stock->amount -= $qtty ;
        $stock->update() ;
    }
    
    public function onProceed($data) {
        if ($this->nation->amount !== null) {
            return $this->onProceedFixed($data) ;
        } else {
            return $this->onProceedInfinite($data) ;
        }
    }

}
