<?php

namespace Services\Game\Forum ;

class AddThread extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("category", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Category::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if (! $this->category->canRW($char)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_FORUM_CATEGORY_FORBIDEN()) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("title", new \Quantyl\Form\Fields\Text(\I18n::TITLE(), true)) ;
        $form->addInput("message", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE(), true)) ;
    }
    
    public function onProceed($data) {
        
        $thread = \Model\Game\Forum\Thread::createFromValues(array(
            "category"  => $this->category,
            "author"    => $this->getCharacter(),
            "title"     => $data["title"],
        )) ;
        
        \Model\Game\Forum\Post::createFromValues(array(
            "thread"        => $thread,
            "pub_author"    => $this->getCharacter(),
            "pub_date"      => time(),
            "content"       => $data["message"]
        )) ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/Game/Forum/Thread?thread={$thread->id}")) ;
        
    }
    
}
