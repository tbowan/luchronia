<?php

namespace Answer\Decorator ;

class BackOffice extends Base {
    
    public function getTplMenu() {
        return \I18n::LM_BACKOFFICE() ;
    }

}
