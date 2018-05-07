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

class AddPost extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("category", new Id(Category::getBddTable())) ;
    }

    public function fillDataForm(Form &$form) {
        $form->addInput("title",     new Text(\I18n::TITLE(), true)) ;
        $form->addInput("category",  new Select(Category::getBddTable(), \I18n::CATEGORY(), true))
             ->setValue($this->category) ;
        $form->addInput("date",      new DateTime(\I18n::DATE(), null, true))
             ->setValue(time());
        $form->addInput("published", new Boolean(\I18n::PUBLISHED())) ;
        $form->addInput("image",     new Text(\I18n::IMAGE())) ;
        $form->addInput("head",      new FullHtml(\I18n::HEAD(), true)) ;
        $form->addInput("content",   new FullHtml(\I18n::CONTENT(), true)) ;
    }
    
    public function onProceed($data) {
        Post::createFromValues(array(
            "title"     => $data["title"],
            "date"      => $data["date"],
            "head"      => $data["head"],
            "content"   => $data["content"],
            "category"  => $data["category"],
            "author"    => $_SESSION["char"],
            "published" => $data["published"],
            "image"     => $data["image"]
        )) ;
    }

}
