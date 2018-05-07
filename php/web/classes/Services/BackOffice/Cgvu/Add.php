<?php

namespace Services\BackOffice\Cgvu ;

class Add extends \Services\Base\Admin {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("file", new \Quantyl\Form\Fields\Text(\I18n::FILE())) ;
        $form->addInput("inserted", new \Quantyl\Form\Fields\DateTime(\I18n::DATE())) ;
    }
    
    public function onProceed($data) {
        
        $cgvu = \Model\Identity\Cgvu::createFromValues(array(
            "file" => $data["file"],
            "inserted" => $data["inserted"]
        ));
        
        return ;
    }
    
    
}

