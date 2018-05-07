<?php

namespace Quantyl\Form ;

class Renderer {
    
    public function render(Form $f, $action = "") {
        return $f->getHTML($action) ;
    }
    
}
