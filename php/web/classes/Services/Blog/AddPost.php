<?php

namespace Services\Blog ;

use Exception;
use Model\Blog\Category;
use Model\Blog\Post;
use Quantyl\Form\Fields\Boolean;
use Quantyl\Form\Fields\DateTime;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Select;
use Quantyl\Request\Request;

class AddPost extends \Services\Base\Character {
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req) ;
        
        $user = $this->getUser() ;
        $cats = Category::getAll();
        $hasaccess = false ;
        foreach ($cats as $c) {
            $hasaccess = $hasaccess || $c->canAccess($user) ;
        }
        
        if (! $hasaccess) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::HAS_NOT_ACCESS_TO_CATEGORY()) ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("title",     new Text(\I18n::TITLE(), true)) ;
        $form->addInput("category",  new Select(Category::getBddTable(), \I18n::CATEGORY(), true));
        $form->addInput("date",      new DateTime(\I18n::DATE(), null, true))
             ->setValue(time());
        $form->addInput("published", new Boolean(\I18n::PUBLISHED())) ;
        $form->addInput("image",     new Text(\I18n::IMAGE())) ;
        $form->addInput("head",      new FullHtml(\I18n::HEAD(), true)) ;
        $form->addInput("content",   new FullHtml(\I18n::CONTENT(), true)) ;
    }
    
    public function onProceed($data) {
        
        $user = $this->getUser() ;
        
        if (! $data["category"]->canAccess($user)) {
            throw new Exception(\I18n::HAS_NOT_ACCESS_TO_CATEGORY()) ;
        }
        
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
