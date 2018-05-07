<?php

namespace Services\BackOffice\Wiki ;

use Model\I18n\Lang;
use Model\Wiki\Page;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Page::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("title", new Text(\I18n::TITLE(), true))
             ->setValue($this->id->title);
        $form->addInput("lang", new Select(Lang::getBddTable(), \I18n::LANG(), true))
             ->setValue($this->id->lang);
        $form->addInput("content", new FullHtml(\I18n::CONTENT()))
             ->setValue($this->id->content);
    }
    
    public function onProceed($data) {
        $this->id->title   = $data["title"] ;
        $this->id->lang    = $data["lang"] ;
        $this->id->content = $data["content"] ;
        $this->id->update() ;
    }
    
}
