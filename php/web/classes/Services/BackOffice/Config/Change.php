<?php

namespace Services\BackOffice\Config ;

class Change extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("config", new \Quantyl\Form\Model\Id(\Model\Quantyl\Config::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::CHANGE_CONFIG_MESSAGE(
                $this->config->key,
                \I18n::translate("CONFIG_" . $this->config->key)
                )) ;
        
        $classname = "\\Quantyl\\Form\\Fields\\{$this->config->type}" ;
        if (class_exists($classname)) {
            $field = new $classname(\I18n::VALUE()) ;
        } else {
            $field = new \Quantyl\Form\Fields\Text(\I18n::VALUE()) ;
        }
        
        $form->addInput("value", $field)
             ->setValue($this->config->value) ;
    }
    
    public function onProceed($data) {
        $this->config->value = $data["value"] ;
        $this->config->update() ;
    }
    
}
