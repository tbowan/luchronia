<?php

namespace Widget\BackOffice\World ;

class ListWorld extends \Quantyl\Answer\Widget {
    
    private $_worlds ;
    
    public function __construct($worlds) {
        $this->_worlds = $worlds ;
    }

    public function getContent() {
        
        $res = "" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::NAME(),
            \I18n::SIZE(),
            \I18n::CITIES(),
            \I18n::ACTIONS()
        )) ;
        
        foreach ($this->_worlds as $w) {
            $size = $w->size ;
            $cities = 12 + 30 * ($size - 1) + 10 * ($size - 1) * ($size - 2) ;
            $table->addRow(array(
                $w->getName(), // new \Quantyl\XML\Html\A("/BackOffice/World/Show?id={$w->id}", $w->name),
                $size ,
                $cities,
                new \Quantyl\XML\Html\A("/BackOffice/World/Delete?id={$w->id}", \I18n::DELETE()),
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }

}
