<?php

namespace Services\BackOffice\Wiki ;

use Model\I18n\Lang;
use Model\Wiki\Page;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Select;

class Add extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("title", new Text(\I18n::TITLE(), false)) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("title", new Text(\I18n::TITLE(), true))
             ->setValue($this->title) ;
        $form->addInput("lang", new Select(Lang::getBddTable(), \I18n::LANG(), true)) ;
        $form->addInput("content", new FullHtml(\I18n::CONTENT())) ;
    }
    
    public function onProceed($data) {
        Page::createFromValues(array(
            "title" => $data["title"],
            "lang" => $data["lang"],
            "content" => $data["content"]
        )) ;
    }
    
}
