<?php

namespace Answer\Decorator  ;

class Blog extends Base {
    
    public function getBlogMenu() {
        $res = "" ;
        $res .= new \Answer\Widget\Blog\Categories($this->getUser(), \Model\I18n\Lang::GetCurrent()) ;
        
        $u = $this->getUser() ;
        if ($u !== null && $u->hasBlogCategoryAccess()) {
            $res .= new \Answer\Widget\Blog\BackOffice() ;
        }
        
        return $res ;
    }

}
