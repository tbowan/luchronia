<?php

namespace Services\Game\Character\Friend ;

use Model\Game\Social\Follower;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Widget\Exception;

class UnFollow extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Follower::getBddTable())) ;
    }
    
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $me = $this->getCharacter() ;
        
        if (! $this->id->a->equals($me)) {
            // TODO : better exception
            throw new \Exception();
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_FOLLOWER_CONFIRM(
                $this->id->b->getId(),
                $this->id->b->getName()
                )) ;
        return $form ;
    }
    
    public function onProceed($data) {
        \Model\Event\Listening::Social_Unfellow($this->id->a,  $this->id->b) ;
        $this->id->delete() ;
    }

}
