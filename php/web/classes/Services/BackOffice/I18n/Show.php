<?php

namespace Services\BackOffice\I18n ;

use Model\I18n\Lang;
use Model\I18n\Translation;
use Quantyl\Form\Fields\Submit;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Widget\BackOffice\I18n\TranslationList;

class Show extends \Services\Base\Admin {

    public function fillParamForm(Form &$form) {
        $form->addInput("lang", new Id(Lang::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::LANG_SHOW_FORM($this->lang->getName(), $this->lang->id)) ;
        $form->addInput("key",          new Text(\I18n::SEARCH_IN_KEYS())) ;
        $form->addInput("translation",  new Text(\I18n::SEARCH_IN_TRANSLATIONS())) ;
        
        $form->addSubmit("send", new Submit(\I18n::SEARCH()))
             ->setCallBack($this, "search");
    }
    
    public function search($data) {
        $this->setAnswer(new TranslationList(
                $this->lang,
                Translation::GetBySearch($this->lang, $data["key"], $data["translation"])
                )) ;
    }
}
