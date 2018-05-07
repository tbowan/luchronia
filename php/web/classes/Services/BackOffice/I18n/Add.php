<?php

namespace Services\BackOffice\I18n ;

use Model\I18n\Lang;
use Model\I18n\Translation;
use Quantyl\Form\Fields\FullHtml;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Add extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("lang", new Id(Lang::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("key", new Text(\I18n::KEY())) ;
        $form->addInput("translation", new \Form\I18nTranslation(\I18n::TRANSLATION()))
             ->setTinyMCE(false) ;
    }
    
    public function onProceed($data) {
        Translation::createTranslation(
            $data["key"],
            $data["translation"],
            $this->lang
            ) ;
    }
}

?>
