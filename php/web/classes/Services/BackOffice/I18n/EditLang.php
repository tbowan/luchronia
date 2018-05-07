<?php

namespace Services\BackOffice\I18n ;

use Model\I18n\Lang;
use Model\I18n\Translation;
use Model\Wiki\Page;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\FieldSet;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;

class EditLang extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("lang", new Id(Lang::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        
        $lang = $this->lang ;
        $form->addInput("mainpage", new Select(Page::getBddTable(), \I18n::MAIN_PAGE()))
             ->setValue($lang->mainpage);
        $form->addInput("wikipage", new Select(Page::getBddTable(), \I18n::WIKI_PAGE()))
             ->setValue($lang->wikipage);
        $form->addInput("dns", new Text(\I18n::I18N_DNS()))
             ->setValue($lang->dns);
        
        // Translation
        $fs = $form->addInput("i18n", new FieldSet(\I18n::I18n())) ;
        $langs = Lang::getAll() ;
        foreach ($langs as $l) {
            $fs->addInput($l->id, new Text($l->getName()))
               ->setValue($l->getTranslation($lang->code)->translation) ;
        }
    }

    public function onProceed($data) {
        
        $this->lang->mainpage = $data["mainpage"] ;
        $this->lang->wikipage = $data["wikipage"] ;
        $this->lang->dns      = $data["dns"] ;
        $this->lang->update() ;
        
        $langs = Lang::getAll() ;
        foreach ($langs as $l) {
            Translation::createTranslation(
                    $this->lang->code,
                    $data["i18n"][$l->id],
                    $l) ;
        }
    }
    
}

?>
