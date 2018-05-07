<?php

namespace Services\BackOffice\Stats ;

class KillScript extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Stats\Script::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->id->isUp()) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_PROCESS_IS_RUNNING($this->id->pid)) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $s = $this->id ;
        $form->addMessage(\I18n::KILL_SCRIPT_MESSAGE(
                $s->hostname,
                $s->script,
                \I18n::_date_time($s->start),
                $s->isUp() ? \I18n::_date_time($s->getEnd()) : \I18n::NOT_RUNNING()
                )) ;
    }
    
    public function onProceed($data, $form) {
        $script = $this->id ;
        $script->killMe() ;
        $script->delete() ;
    }
    
}
