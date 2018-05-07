<?php

namespace Services\Forum ;

class Main extends \Services\Base\Character {
    
    public function getView() {
        return new \Answer\View\Forum\CategoryRoot($this) ;
    }
    
}
