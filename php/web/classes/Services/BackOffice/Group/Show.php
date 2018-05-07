<?php

namespace Services\BackOffice\Group ;

use Model\Identity\Group;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Widget\BackOffice\Group\GroupDetail;

class Show extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Group::getBddTable(), "", true)) ;
    }

    public function getView() {
        return new GroupDetail($this->id) ;
    }
    
    public function getTitle() {
        if ($this->_group !== null) {
            return $this->_group->name ;
        } else {
            return parent::getTitle() ;
        }
    }
    
}
