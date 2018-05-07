<?php

namespace Services\Game\Character\Friend ;

use Exception;
use Model\Game\Character;
use Model\Game\Social\Friend;
use Model\Game\Social\Request as MRequest;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request as QRequest;

class Request extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Character::getBddTable())) ;
    }
    
    public function checkPermission(QRequest $req) {
        parent::checkPermission($req);
        
        $char = $this->getCharacter() ;
        
        // TODO : better exception
        if ($this->id->equals($char)) {
            // Character are same
            throw new Exception() ;
        } else if (Friend::GetFromAB($char, $this->id) != null) {
            // Already connected
            throw new Exception() ;
        } else if (MRequest::GetFromAB($char, $this->id) != null) {
            // Already requested by me
            throw new Exception() ;
        } else if (MRequest::GetFromAB($this->id, $char) != null) {
            // Already requested by him
            throw new Exception() ;
        }
        
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("msg", new FilteredHtml(\I18n::MESSAGE())) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $request = MRequest::createFromValues(array (
            "a"   => $this->getCharacter(),
            "b"   => $this->id,
            "msg" => $data["msg"]
        )) ;
        
       \Model\Event\Listening::Social_Friendship_Request($request) ; 
    }
}
