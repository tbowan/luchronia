<?php

namespace Services\BackOffice\I18n ;

use Model\I18n\Translation;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Edit extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("translation", new Id(Translation::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::EDIT_TRANSLATION_MSG($this->translation->key)) ;
        $form->addInput("translation", new \Form\I18nTranslation(\I18n::TRANSLATION()))
             ->setTinyMCE(false)
             ->setValue($this->translation->translation);
    }
    
    public function onProceed($data) {
        $this->translation->translation = $data["translation"] ;
        $this->translation->update() ;
    }
}
