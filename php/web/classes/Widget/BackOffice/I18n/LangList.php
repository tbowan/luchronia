<?php

namespace Widget\BackOffice\I18n ;

class LangList extends \Quantyl\Answer\Widget {
    
    private $_langs ;
    
    public function __construct($langs) {
        $this->_langs = $langs ;
    }
    
    public function getContent() {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::LANG(),
            \I18n::ACTIONS()
                )) ;

        foreach ($this->_langs as $l) {
            $table->addRow(array(
                $l->getImage("icone-inline") . " " . $l->getName(),
                "<a href=\"/BackOffice/I18n/Show?lang={$l->id}\">" . \I18n::VIEW()  . "</a>" .
                " | <a href=\"/BackOffice/I18n/EditLang?lang={$l->id}\">" . \I18n::EDIT() . "</a>" .
                " | <a href=\"/BackOffice/I18n/Translate?lang={$l->id}\">" . \I18n::COMPLETE_TRANSLATION() . "</a>"
            )) ;
        }

        return "" . $table;
    }
    
    
}
