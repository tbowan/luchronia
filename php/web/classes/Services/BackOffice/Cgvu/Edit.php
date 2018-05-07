<?php

namespace Services\BackOffice\Cgvu ;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Identity\Cgvu::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("file", new \Quantyl\Form\Fields\Text(\I18n::FILE()))
             ->setValue($this->id->file);
        $form->addInput("inserted", new \Quantyl\Form\Fields\DateTime(\I18n::DATE()))
             ->setValue($this->id->inserted);
    }
    
    public function onProceed($data) {
        
        $this->id->file = $data["file"] ;
        $this->id->inserted = $data["inserted"] ;
        $this->id->update() ;
        
        return ;
    }
    
    
}

