<?php

namespace Services\User\Create ;

class Main extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("code",     new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function getView() {
        return new \Quantyl\Answer\Redirect("/User/Create/Step1?code=" . urlencode($this->code)) ;
    }
    
}
