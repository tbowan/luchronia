<?php

namespace Widget\BackOffice\Cgvu ;

class Table extends \Quantyl\Answer\Widget {
    
    
    public function __construct() {
        ;
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::CGVU_TABLE() . "</h2>" ;
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Cgvu/Add", \I18n::ADD()) ;

        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::DATE(),
            \I18n::FILE(),
            \I18n::ACTIONS()
        )) ;
        
        foreach (\Model\Identity\Cgvu::GetAll() as $cgvu) {
            $table->addRow(array(
                \I18n::_date_time($cgvu->inserted),
                new \Quantyl\XML\Html\A($cgvu->file, \I18n::DOWNLOAD()),
                new \Quantyl\XML\Html\A("/BackOffice/Cgvu/Edit?id={$cgvu->id}", \I18n::EDIT())
                . new \Quantyl\XML\Html\A("/BackOffice/Cgvu/Delete?id={$cgvu->id}", \I18n::DELETE())
                )) ;
        }
        $res .= $table ;
        
        return $res ;
        
    }
    
}