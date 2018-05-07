<?php

namespace Answer\Widget\Game\City ;

class Message extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\TownHall $townhall, $classes = "") {
        
        if ($townhall->welcome == "") {
            $res = \I18n::NO_TOWNHALL_MESSAGE() ;
        } else {
            $res = $townhall->welcome ;
        }
        
        parent::__construct($townhall->name, "", "", $res, $classes) ;
    }
    
}
