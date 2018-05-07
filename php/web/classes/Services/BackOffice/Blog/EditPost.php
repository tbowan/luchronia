<?php

namespace Services\BackOffice\Blog ;

use Model\Blog\Category;
use Model\Blog\Post;
use Quantyl\Form\Fields\Boolean;
use Quantyl\Form\Fields\DateTime;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

class EditPost extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("post", new Id(Post::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("title",     new Text(\I18n::TITLE(), true))
             ->setValue($this->post->title);
        $form->addInput("category",  new Select(Category::getBddTable(), \I18n::CATEGORY(), true))
             ->setValue($this->post->category) ;
        $form->addInput("date",      new DateTime(\I18n::DATE(), null, true))
             ->setValue($this->post->date);
        $form->addInput("published", new Boolean(\I18n::PUBLISHED()))
             ->setValue($this->post->published);
        $form->addInput("image",     new Text(\I18n::IMAGE()))
             ->setValue($this->post->image);
        $form->addInput("head",      new FullHtml(\I18n::HEAD(), true))
             ->setValue($this->post->head);
        $form->addInput("content",   new FullHtml(\I18n::CONTENT(), true))
             ->setValue($this->post->content);
    }
    
    public function onProceed($data) {
        
        $this->post->title     = $data["title"] ;
        $this->post->category  = $data["category"] ;
        $this->post->date      = $data["date"] ;
        $this->post->published = $data["published"] ;
        $this->post->head      = $data["head"] ;
        $this->post->content   = $data["content"] ;
        $this->post->image     = $data["image"] ;
        
        $this->post->update() ;
    }

}
