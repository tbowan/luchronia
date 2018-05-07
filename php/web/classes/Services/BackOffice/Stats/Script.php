<?php

namespace Services\BackOffice\Stats ;

class Script extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("script", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function getView() {
        return new \Widget\BackOffice\Stats\Scripts(\I18n::BACKOFFICE_STATS_SCRIPTS(), \Model\Stats\Script::GetByScript($this->script), "") ;
    }
    
}
