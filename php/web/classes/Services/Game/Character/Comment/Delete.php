<?php

namespace Services\Game\Character\Comment ;

use Model\Game\Social\Comment;
use Quantyl\ACL\Admin;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Delete extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Comment::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        $char = $this->getCharacter() ;
        
        $author = $this->id->post->author->equals($char) ;
        $admin = new Admin($req->getServer()->getConfig()) ;
        
        if (! ($author || $admin) ) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
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
