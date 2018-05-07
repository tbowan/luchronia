<?php

namespace Model\Game\Politic ;

class QuestionType extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        1 => "Government" ,
        2 => "System" ,
        3 => "President" ,
        4 => "Parliament" ,
    ) ;
    
    public function getPrefix() {
        return "QUESTIONTYPE_" ;
    }

}
