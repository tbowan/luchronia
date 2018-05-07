<?php

namespace Answer\Widget\Misc ;

class CardImage extends Card {
    
    public function __construct($head = null, $image = null, $message = null) {
        $res  = "<div class=\"card-image\">" ;
        $res .= $image ;
        $res .= "</div>" ;
        $res .= "<div class=\"card-message\">" ;
        $res .= $message ;
        $res .= "</div>" ;
        parent::__construct($head, $res);
    }

}
