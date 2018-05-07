<?php

namespace Services\Game\Character\Comment ;

use Model\Game\Social\Comment;
use Model\Game\Social\Post;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class Add extends \Services\Base\Character {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("post", new Id(Post::getBddTable())) ;
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        $char = $this->getCharacter() ;
        if (! $this->post->access->hasCharacterAccess($this->post->author, $char)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("content", new FilteredHtml(\I18n::CONTENT())) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $comment = new Comment() ;
        $comment->post    = $this->post ;
        $comment->date    = time() ;
        $comment->author  = $_SESSION["char"] ;
        $comment->content = $data["content"] ;
        $comment->create() ;
        
        \Model\Event\Listening::Social_Wall_Comment($comment) ;
    }
}
