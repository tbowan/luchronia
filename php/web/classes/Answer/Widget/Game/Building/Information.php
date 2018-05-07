<?php

namespace Answer\Widget\Game\Building ;

class Information extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $classes = "") {
        $res  = "" ;
        $res .= "<ul>" ;
        
        $res .= "<li><strong>" . \I18n::NAME() . " :</strong> " . $instance->getName() . " </li>" ;
        $res .= "<li><strong>" . \I18n::POSITION() . " :</strong> " . new \Quantyl\XML\Html\A("/Game/City?id={$instance->city->id}", $instance->city->getName()) . " </li>" ;
        $res .= "<li><strong>" . \I18n::LEVEL()  . " :</strong> " . $instance->level . "</li>" ;
        $res .= "<li><strong>" . \I18n::HEALTH()  . " :</strong> " . $instance->getHealth() . " / " . $instance->getMaxHealth() . "</li>" ;
        $res .= "<li><strong>" . \I18n::BARRICADE()  . " :</strong> " . number_format($instance->barricade, 2) . " / " . $instance->getHealth() . "</li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_RESISTANCE()  . " :</strong> " . number_format(100 * $instance->getWear(), 2) . " %</li>" ;
        $res .= "</ul>" ;
        
        parent::__construct(
                \I18n::INFORMATIONS(),
                "",
                "",
                $res,
                $classes);
    }
    
}
