<?php

namespace Answer\Widget\Game\Building ;

class Field extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $classes = "") {
        
        $field = \Model\Game\Building\Field::GetFromInstance($instance) ;
        
        $res = "<ul class=\"itemList\">" ;
        $res .= "<li><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $field->item->getImage() . "</div>" ;
        $res .= "<div class=\"description\">"
                . "<div class=\"name\">" . $field->item->getName() . "</div>"
                . "<div class=\"amount\">" . \I18n::AMOUNT() . " : " . $field->amount . " / " . $field->getMaxAmount() . "</div>"
                . "</div>" ;

        $res .= "</div></li>" ;
        
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::FIELD_INFO(), "", "", $res, $classes);
    }
    
}
