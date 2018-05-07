<?php

namespace Services\Game\Ministry\Building\TradingPost ;

class Remove extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("order", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Tradingpost::getBddTable())) ;
    }
    
    public function getView() {
        $this->order->delete() ;
    }

    public function getCity() {
        return $this->order->instance->city ;
    }

    public function getMinistry() {
        return $this->order->instance->job->ministry ;
    }

}
