<?php

namespace Services\Game\Character\Post ;

use Model\Enums\Access;
use Model\Game\Social\Post;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Select;

class Add extends \Services\Base\Character {
    
    public function fillDataForm(Form &$form) {
        $form->addInput("content", new FilteredHtml(\I18n::CONTENT())) ;
        $form->addInput("access",  new Select(Access::getBddTable(), \I18n::ACCESS())) ;
        return $form ;
    }
    
    public function onProceed($data) {
        $post = Post::newPost($this->getCharacter(), $data["content"], $data["access"]) ;
        \Model\Event\Listening::Social_Wall_Publication($post) ;
    }
    
}
