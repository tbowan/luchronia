<?php

namespace Services\Game\Post ;

class DelMail extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("inbox", new \Quantyl\Form\Model\Id(\Model\Game\Post\Inbox::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->inbox->box->character->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::DEL_MAIL_MESSAGE(
                $this->inbox->mail->title,
                $this->inbox->mail->author->id,
                $this->inbox->mail->author->getName()
                )) ;
    }
    
    public function onProceed($data) {
        
        $mail = $this->inbox->mail ;
        
        $this->inbox->delete() ;
        
        $remain = \Model\Game\Post\Inbox::CountFromMail($mail) ;
        if ($remain == 0) {
            $mail->delete() ;
        }
        
    }
}
