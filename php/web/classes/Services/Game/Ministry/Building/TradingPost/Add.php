<?php

namespace Services\Game\Ministry\Building\TradingPost ;

class Add extends \Services\Base\Minister {
    
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
        
        // Check remain orders
        $tp = \Model\Game\Building\Tradingpost::GetFromInstance($this->instance) ;
        if ($tp->getTradingUsed() >= $tp->getTradingMax()) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_TRADINGPOST_FULL()) ;
        }
    }
    
    public function getView() {
        \Model\Game\Trading\Tradingpost::createFromValues(array(
            "instance" => $this->instance,
            "trading" => $this->nation
        )) ;
    }

    public function getCity() {
        return $this->instance->city ;
    }

    public function getMinistry() {
        return $this->instance->job->ministry ;
    }

}
