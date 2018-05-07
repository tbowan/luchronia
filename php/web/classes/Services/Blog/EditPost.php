<?php

namespace Services\Blog ;

use Model\Blog\Category;
use Model\Blog\Post;
use Quantyl\Form\Fields\Boolean;
use Quantyl\Form\Fields\DateTime;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

class EditPost extends \Services\Base\BlogAccess {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new Id(Post::getBddTable())) ;
    }
    
    public function getCategory() {
        if ($this->id != null) {
            return $this->id->category ;
        } else {
            return null ;
        }
    }
    public function fillDataForm(Form &$form) {
        $form->addInput("title",     new Text(\I18n::TITLE(), true))
             ->setValue($this->id->title);
        $form->addInput("category",  new Select(Category::getBddTable(), \I18n::CATEGORY(), true))
             ->setValue($this->id->category) ;
        $form->addInput("date",      new DateTime(\I18n::DATE(), null, true))
             ->setValue($this->id->date);
        $form->addInput("published", new Boolean(\I18n::PUBLISHED()))
             ->setValue($this->id->published);
        $form->addInput("image",     new Text(\I18n::IMAGE()))
             ->setValue($this->id->image);
        $form->addInput("head",      new FullHtml(\I18n::HEAD(), true))
             ->setValue($this->id->head);
        $form->addInput("content",   new FullHtml(\I18n::CONTENT(), true))
             ->setValue($this->id->content);
    }
    
    public function onProceed($data) {
        
        $user = $this->getUser() ;
        
        if (! $data["category"]->canAccess($user)) {
            throw new \Exception(\I18n::HAS_NOT_ACCESS_TO_CATEGORY()) ;
        }
        
        $this->id->title     = $data["title"] ;
        $this->id->category  = $data["category"] ;
        $this->id->date      = $data["date"] ;
        $this->id->published = $data["published"] ;
        $this->id->head      = $data["head"] ;
        $this->id->content   = $data["content"] ;
        $this->id->image     = $data["image"] ;
        
        $this->id->update() ;
    }

}
