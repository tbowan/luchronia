<?php

namespace Services\BackOffice\Wiki ;

class Merge extends \Services\Base\Admin {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::WIKI_MERGE_MESSAGE()) ;
        
        $f1 = $form->addInput("page1", new \Quantyl\Form\FieldSet(\I18n::WIKI_MERGE_PAGE_1())) ;
        $f1->addInput("lang", new \Quantyl\Form\Model\Select(\Model\I18n\Lang::getBddTable(), \I18n::LANG())) ;
        $f1->addInput("title", new \Quantyl\Form\Fields\Text(\I18n::TITLE())) ;
        
        $f2 = $form->addInput("page2", new \Quantyl\Form\FieldSet(\I18n::WIKI_MERGE_PAGE_2())) ;
        $f2->addInput("lang", new \Quantyl\Form\Model\Select(\Model\I18n\Lang::getBddTable(), \I18n::LANG())) ;
        $f2->addInput("title", new \Quantyl\Form\Fields\Text(\I18n::TITLE())) ;
    }
    
    public function onProceed($data) {
        
        $p1 = \Model\Wiki\Page::GetByLangAndName($data["page1"]["lang"], $data["page1"]["title"]) ;
        $p2 = \Model\Wiki\Page::GetByLangAndName($data["page2"]["lang"], $data["page2"]["title"]) ;
        
        if ($p1 == null) {
            throw new \Exception(\I18n::WIKI_MERGE_NOT_FOUND_1()) ;
        } else if ($p2 == null) {
            throw new \Exception(\I18n::WIKI_MERGE_NOT_FOUND_2()) ;
        } else if (! \Model\Wiki\Page::canMerce($p1, $p2) ) {
            throw new \Exception(\I18n::WIKI_MERGE_CANNOT()) ;
        }
        
        \Model\Wiki\Page::Merge($p1, $p2) ;
        
    }
    
}
