<?php

namespace Services\Game\Post ;

class ChoseRecipient extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable(), "", false)) ;
        $form->addInput("next", new \Quantyl\Form\Fields\Text()) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->instance != null && ! $this->instance->city->equals($this->getCharacter()->position)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("recipient", new \Form\Character\Friend($this->getCharacter(), \I18n::RECIPIENT())) ;
    }
    
    public function onProceed($data) {
        
        $url = $this->next ;
        $url .= (strstr($url, "?") !== false ? "&" : "?" );
        $url .= "recipient=" . $data["recipient"]->id ;
        $url .= "&referer=" . urlencode($this->getRequest()->getReferer()) ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect($url)) ;
    }
    
}
