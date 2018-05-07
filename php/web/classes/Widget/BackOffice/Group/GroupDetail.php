<?php

namespace Widget\BackOffice\Group ;

use \Model\Identity\Group ;
use \Model\Identity\Role ;

class GroupDetail extends \Quantyl\Answer\Widget {
    
    private $_group ;
    
    public function __construct(Group $group) {
        $this->_group = $group ;
    }
    
    public function getContent() {
        
        $res = $this->_group->description ;
        
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Group/Edit?id={$this->_group->id}", \I18n::EDIT()) ;
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Group/Delete?id={$this->_group->id}", \I18n::DELETE()) ;
        $res .= new \Quantyl\XML\Html\Head(2, \I18n::USER_LIST()) ;
        $res .= new \Quantyl\XML\Html\A("/BackOffice/Group/Join?group={$this->_group->id}", \I18n::ADD()) ;
        
        $roles = Role::GetFromGroup($this->_group) ;
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(\I18n::USER(), \I18n::ACTIONS())) ;
        
        foreach ($roles as $r) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/BackOffice/User/Show?id={$r->user->id}", $r->user->getName()),
                new \Quantyl\XML\Html\A("/BackOffice/Group/Remove?id={$r->id}", \I18n::DELETE())
            )) ;
        }
        $res .= $table ;
        
        return $res ;
    }
    
}
