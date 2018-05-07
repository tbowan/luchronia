<?php

namespace Services\Game\Character\Post ;

use Model\Game\Social\Post;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Widget\Exception;

class Delete extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Post::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        if (! $this->id->author->equals($me)) {
            // TODO : better exception
            throw new Exception() ;
        }
        
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->content)) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }
    
}
