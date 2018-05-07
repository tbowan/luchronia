<?php

namespace Services\BackOffice\I18n ;

class Translate extends \Services\Base\Admin {
    
    private $_key ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("lang", new \Quantyl\Form\Model\Id(\Model\I18n\Lang::getBddTable())) ;
    }
    
    public function init() {
        parent::init();
        
        $this->_key = \Model\I18n\Translation::GetFirstUntranslated($this->lang) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->_key == "") {
            throw new \Quantyl\Exception\Http\ClientBadRequest(\I18n::NOTHING_TO_TRANSLATE($this->lang->getName())) ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $msg = "" ;
        $msg .= \I18n::TRANSLATE_KEY_MESSAGE($this->_key) ;
        foreach (\Model\I18n\Translation::GetFromKey($this->_key) as $t) {
            $msg .= "<h3>" . $t->lang->getName() . "</h3>" ;
            $msg .= "<pre>" . htmlspecialchars($t->translation, ENT_QUOTES, "UTF-8") . "</pre>" ;
        }
        $form->setMessage($msg) ;
        $form->addInput("translation", new \Quantyl\Form\Fields\FullHtml(\I18n::TRANSLATION()))
             ->setTinyMCE(false) ;
    }
    
    public function onProceed($data) {
        \Model\I18n\Translation::createTranslation(
            $this->_key,
            $data["translation"],
            $this->lang
            ) ;
        
        $this->setAnswer(new \Quantyl\Answer\Redirect("/BackOffice/I18n/Translate?lang={$this->lang->id}")) ;
    }
    
}
