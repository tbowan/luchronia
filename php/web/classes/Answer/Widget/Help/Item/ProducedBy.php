<?php

namespace Answer\Widget\Help\Item;

class ProducedBy extends \Answer\Widget\Misc\Card {
    
    public function __construct($skills) {
        
        $msg = "" ;
        $msg .= \I18n::HELP_PRODUCEDBY_MESSAGE() ;
        $msg .= "<ul>" ;
        foreach ($skills as $skill) {
            $msg .= "<li>" . $skill->getImage("icone-inline") . " " . $skill->getName() . " " . \I18n::Help("/Help/Skill?id={$skill->id}") . "</li>" ;
        }
        $msg .= "</ul>" ;
        
        parent::__construct(\I18n::HELP_PRODUCEDBY(), $msg);
    }
    
}
