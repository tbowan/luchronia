<?php

namespace Services\Game\Character\Post ;

use Model\Enums\Access;
use Model\Game\Social\Post;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;
use Quantyl\Request\Request;
use Widget\Exception;

class Edit extends \Services\Base\Character {
    
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
        $form->addInput("content", new FilteredHtml(\I18n::CONTENT()))
             ->setValue($this->id->content) ;
        $form->addInput("access",  new Select(Access::getBddTable(), \I18n::ACCESS()))
             ->setValue($this->id->access) ;
        return $form ;
    }
    
    public function onProceed($data) {
        
        $this->id->content = $data["content"] ;
        $this->id->access  = $data["access"] ;
        $this->id->update() ;
    }
    
}
