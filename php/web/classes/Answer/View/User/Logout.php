<?php

namespace Answer\View\User ;

class Logout extends \Answer\View\Base {
    
    public function getTplContent() {
        
        return new \Answer\Widget\Misc\Section(\I18n::LOGOUT_SUCESSFULL(), "", "", \I18n::LOGOUT_SUCCESS_MESSAGE()) ;
        
    }

    public function getTplMenu() {
        return \I18n::LM_MAIN() ;
    }

}
