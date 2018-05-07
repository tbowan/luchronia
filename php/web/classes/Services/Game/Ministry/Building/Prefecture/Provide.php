<?php

namespace Services\Game\Ministry\Building\Prefecture ;

class Provide extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("site", new \Quantyl\Form\Model\Id(\Model\Game\Building\Site::getBddTable())) ;
        $form->addInput("prefecture", new \Quantyl\Form\Model\Id(\Model\Game\Building\Prefecture::getBddTable())) ;
    }

    public function getCity() {
        return $this->prefecture->instance->city ;
    }

    public function getMinistry() {
        return $this->prefecture->instance->job->ministry ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (!\Model\Game\City\Prefecture::IsCloseEnough($this->site->instance->city, $this->prefecture)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_PREFECTURE_DISTANCE_TO_HIGH()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addMessage(\I18n::PREFECTURE_GRANT_MESSAGE()) ;
        
        $items = $form->addInput("items", new \Quantyl\Form\FieldSet()) ;
        $instance = $this->prefecture->instance ;
        foreach (\Model\Game\Building\Need::GetFromSite($this->site) as $need) {
            $remain = $need->needed - $need->provided ;
            $items->addInput($need->id, new \Form\BuildingStock($need->item, $instance, $need->item->getName() . " ($remain)")) ;
        }
        
    }
    
    public function onProceed($data) {
        
        foreach (\Model\Game\Building\Need::GetFromSite($this->site) as $need) {
            $remain = $need->needed - $need->provided ;
            $stock = $data["items"][$need->id] ;
            if ($stock != null) {
                $provided = min($remain, $stock->amount) ;
                $stock->amount -= $provided ;
                $stock->update() ;
                
                $need->provided += $provided ;
                $need->update() ;
            }
        }
        
    }
}