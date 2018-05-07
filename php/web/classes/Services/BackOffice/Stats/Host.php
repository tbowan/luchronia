<?php

namespace Services\BackOffice\Stats ;

class Host extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("hostname", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function getView() {
        return new \Widget\BackOffice\Stats\Scripts(\I18n::BACKOFFICE_STATS_SCRIPTS(), \Model\Stats\Script::GetByHostname($this->hostname), "") ;
    }
    
}
