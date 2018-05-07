<?php

namespace Services\User\Create ;

use Form\StartPosition;
use Model\Enums\Race;
use Model\Enums\Sex;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Enum;
use Quantyl\Service\EnhancedService;

abstract class CreateStep extends EnhancedService {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("race",     new Enum(Race::getBddTable(), "", false)) ;
        $form->addInput("sex",      new Enum(Sex::getBddTable(), "", false)) ;
        $form->addInput("avatar",   new \Form\Avatar(null, null, false)) ;
        $form->addInput("name",     new Text()) ;
        $form->addInput("code",     new Text()) ;
        $form->addInput("position", new StartPosition()) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Check invitation
        $inv_needed = \Model\Quantyl\Config::ValueFromKey("INVITATION_NEEDED", false) ;
        
        if ($inv_needed && ! \Model\Identity\Sponsor::canUseInvitation($this->code)) {
            
            // 1. have a code
            $f1 = new Form() ;
            $f1->setAction("/User/Create", "get") ;
            $f1->addInput("code", new Text(\I18n::CODE())) ;
            $f1->addSubmit("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
            $msg1 = new \Answer\Widget\Misc\Card(\I18n::HAVE_CODE_TITLE(), \I18n::HAVE_CODE_MESSAGE($f1->getContent())) ;
            
            // 2. havent a code
            $f2 = new Form() ;
            $f2->setAction("/User/RequestCode") ;
            $f2->addInput("mail", new Text(\I18n::EMAIL())) ;
            $f2->addInput("mailbis", new Text(\I18n::EMAIL_BIS())) ;
            $f2->addInput("message", new \Quantyl\Form\Fields\TextArea(\I18n::MESSAGE())) ;
            $f2->addSubmit("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
            $msg2 = new \Answer\Widget\Misc\Card(\I18n::HAVENT_CODE_TITLE(), \I18n::HAVENT_CODE_MESSAGE($f2->getContent())) ;
            
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_INVITATION_NEEDED($msg1 . $msg2)) ;
        }
    }
    
    private function atrToUrl($name) {
        $atr = $this->$name ;
        if ($atr === null) {
            return "" ;
        } else {
            return $atr->getId() ;
        }
    }
    
    public function urlencodeParams() {
        $race  = urlencode($this->atrToUrl("race")) ;
        $sex   = urlencode($this->atrToUrl("sex")) ;
        $name  = urlencode($this->name) ;
        $start = urlencode($this->atrToUrl("position")) ;
        $code  = urlencode($this->code) ;
        $avatar = "" ;
        foreach ($this->avatar as $k => $value) {
            if (is_int($k) && $value != null) {
                $avatar .= "&avatar[$k]=" . $value->getId() ;
            }
        }
        return ""
                . "race=$race&"
                . "sex=$sex&"
                . "name=$name&"
                . "position=$start&"
                . "code=$code"
                . "$avatar" ;
    }
    
    public abstract function getNextUrl() ;
    
    public abstract function getPrevUrl() ;

    public abstract function createData($data) ;
    
    public function addSubmit(Form &$form) {
        $form->addSubmit("prev", new \Quantyl\Form\Fields\Submit(\I18n::PREV_STEP(), false))
             ->setCallBack($this, "onPrev") ;
        $form->addSubmit("next", new \Quantyl\Form\Fields\Submit(\I18n::NEXT_STEP()))
             ->setCallBack($this, "onNext") ;
    }
    
    public function onPrev($data) {
        $url    = $this->getPrevUrl() ;
        $params = $this->urlencodeParams() ;
        $this->setAnswer(new Redirect($url . "?" . $params)) ;
    }
    
    public function onNext($data) {
        $this->createData($data) ;
        $url    = $this->getNextUrl() ;
        $params = $this->urlencodeParams() ;
        $this->setAnswer(new Redirect($url . "?" . $params)) ;
    }
    
}