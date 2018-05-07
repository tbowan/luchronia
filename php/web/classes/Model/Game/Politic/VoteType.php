<?php

namespace Model\Game\Politic ;

class VoteType extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        0 => "System",
        1 => "Majority",
        2 => "Alternative" ,
        3 => "Borda" ,
        4 => "Cumulative" ,
        5 => "Hare" ,
    ) ;

    public function getPrefix() {
        return "VOTE_TYPE_" ;
    }

}
