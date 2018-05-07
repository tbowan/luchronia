<?php

namespace Form\Renderer ;

class TwoSide extends \Quantyl\Form\Renderer {
    
    public function render(\Quantyl\Form\Form $f, $action = "") {
        return $f->getHTML($action, "QFormTwoSide") ;
    }
    
}
