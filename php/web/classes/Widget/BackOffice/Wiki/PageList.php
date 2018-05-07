<?php

namespace Widget\BackOffice\Wiki ;

use Quantyl\Answer\Widget;
use Quantyl\XML\Html\A;
use Quantyl\XML\Html\Table;

class PageList extends Widget {
    
    private $_pages ;
    
    public function __construct($pages) {
        $this->_pages = $pages ;
    }
    
    public function getContent() {
        
        $res = "<p>" ;
        $res .= new A("/BackOffice/Wiki/Add", \I18n::ADD()) ;
        $res .= " / " ;
        $res .= new A("/BackOggice/Wiki/Merge", \I18n::WIKI_MERGE()) ;
        $res .= "</p>" ;
        
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::LANG(),
            \I18n::TITLE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach ($this->_pages as $p) {
            $table->addRow(array(
                $p->lang->getImage("icone-inline") . " " . $p->lang->getName(),
                new A("/Wiki/{$p->title}", $p->title),
                new A("/BackOffice/Wiki/Edit?id={$p->id}", \I18n::EDIT()) .
                new A("/BackOffice/Wiki/Delete?id={$p->id}", \I18n::Delete())
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
        
    }
}
