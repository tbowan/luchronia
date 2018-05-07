<?php

namespace Services\Game\Character\Friend ;

use Model\Game\Social\Friend;
use Model\Game\Social\Request as Request2;
use Quantyl\Form\Fields\Submit;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Widget\Exception;

class Accept extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Request2::getBddTable())) ;
    }
    
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $char = $this->getCharacter() ;
        
        if (! $this->id->b->equals($char)) {
            // TODO : better exception
            throw new Exception() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::ACCEPT_FRIEND_CONFIRM(
                $this->id->a->id,
                $this->id->a->getName(),
                \I18n::_date_time($this->id->date),
                $this->id->msg
                )) ;
        
        $form->addSubmit("yes", new Submit(\I18n::YES()))
             ->setCallBack($this, "onYes") ;
        $form->addSubmit("no", new Submit(\I18n::NO()))
             ->setCallBack($this, "onNo") ;
        $form->addSubmit("later", new Submit(\I18n::LATER()))
             ->setCallBack($this, "onLater") ;
        
        return $form ;
    }
    
    public function onYes($data) {
        $friend = Friend::createFromValues(array(
            "a" => $this->id->a,
            "b" => $this->id->b,
            "date" => time()
        )) ;
        \Model\Event\Listening::Social_Friendship_Accept($friend) ;         
        $this->id->delete() ;
    }
    
    public function onNo($data) {
        \Model\Event\Listening::Social_Friendship_Refuse($this->id->a, $this->id->b) ;  
        $this->id->delete() ;
    }
    
    public function onLater($data) {
        return ;
    }

}
