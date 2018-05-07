<?php

namespace Services\Game\Character\Friend ;

use Exception;
use Model\Game\Social\Friend;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Unfriend extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Friend::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        
        if (! $this->id->a->equals($me)) {
            // TODO better exception
            throw new Exception() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_FRIEND_CONFIRM($this->id->b->getName())) ;
        return $form ;
    }
    
    public function onProceed($data) {
        \Model\Event\Listening::Social_Friendship_Suppress($this->id->a, $this->id->b) ;
        $this->id->delete() ;
    }

}
