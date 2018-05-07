<?php

namespace Widget\BackOffice\Group ;

class GroupList extends \Quantyl\Answer\Widget {
    
    private $_groups ;
    
    public function __construct($groups) {
        $this->_groups = $groups ;
    }
    
    public function getContent() {
        
        $res = "" ;
        
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Group/Add", \I18n::ADD()) ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::GROUP(),
            \I18n::ACTIONS()
        ));
        
        foreach ($this->_groups as $g) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/BackOffice/Group/Show?id={$g->id}", $g->name),
                new \Quantyl\XML\Html\A("/BackOffice/Group/Edit?id={$g->id}", \I18n::EDIT()) . " " .
                new \Quantyl\XML\Html\A("/BackOffice/Group/Delete?id={$g->id}", \I18n::DELETE())
                        
            )) ;
        }
        
        $res .= $table ;
        return $res ;
    }
    
}
