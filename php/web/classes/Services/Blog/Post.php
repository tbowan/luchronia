<?php

namespace Services\Blog ;

use Model\Blog\Post as BPost;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Service\EnhancedService;
use Widget\Blog\Post as WPost;

class Post extends EnhancedService {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(BPost::getBddTable())) ;
    }
    
    public function getView() {
        
        return new \Answer\View\Blog\Post($this, $this->id) ;
        
        $user = (isset($_SESSION["user"]) ? $_SESSION["user"] : null) ;
        return new WPost($this->id, $this->id->category->canAccess($user)) ;
    }
    
    public function getTitle() {
        if ($this->id === null) {
            return parent::getTitle() ;
        } else {
            return $this->id->getName() ;
        }
    }
    
}

?>
