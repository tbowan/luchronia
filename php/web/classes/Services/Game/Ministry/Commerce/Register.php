<?php

namespace Services\Game\Ministry\Commerce ;

class Register extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function getCity() {
        return $this->city ;
    }
    
    public function getView() {
        return new \Answer\Widget\Game\Ministry\Commerce\Register($this->city) ;
    }

    public function getTitle() {
        if ($this->city == null) {
            return parent::getTitle();
        } else {
            return \I18n::REGISTER_OF($this->city->getName()) ;
        }
    }

}
