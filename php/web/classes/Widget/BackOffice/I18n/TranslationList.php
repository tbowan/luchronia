<?php

namespace Widget\BackOffice\I18n ;

use \Quantyl\XML\Html\A ;

class TranslationList extends \Quantyl\Answer\Widget {
    
    private $_lang ;
    private $_translations ;
    
    public function __construct(\Model\I18n\Lang $lang, $translations) {
        $this->_lang            = $lang ;
        $this->_translations    = $translations ;
    }
    
    public function getContent() {
        
        $res = "" ;
        $lid = $this->_lang->id ;
        
        $res .= new A(
                        "/BackOffice/I18n/Add?lang={$lid}",
                        \I18n::ADD_TRANSLATION()
                     ) ;
        $res .= new A(
                        "/BackOffice/I18n/Show?lang={$lid}",
                        \I18n::DO_ANOTHER_SEARCH()
                     ) ;
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array (\I18n::KEY(), \I18n::VALUE(), \I18n::ACTIONS())) ;

        
        foreach ($this->_translations as $tr) {
            $k = urlencode($tr->key) ;
            $table->addRow(array(
                $tr->key,
                $tr->translation,
                new A ("/BackOffice/I18n/Edit?translation={$tr->id}", \I18n::EDIT()) . " "
                . new A ("/BackOffice/I18n/Delete?translation={$tr->id}", \I18n::DELETE())
            )) ;
        }

        $res .= $table ;
        return $res ;
    }
    
    
}
