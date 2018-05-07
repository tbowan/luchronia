<?php

namespace Answer\Decorator ;

class Game extends Base {
    
    public function getTplMenu() {
        return \I18n::LM_GAME() ;
    }

}
