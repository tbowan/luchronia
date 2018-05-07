<?php

namespace Answer\Widget\Game\City ;

class Geography extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\City $c, $classes = "") {
        
        $res  = "<object class=\"full\" type=\"image/svg+xml\" data=\"/Game/Map/City?id={$c->id}\" ></object>" ;
        //$res .= "<p class=\"legend\">" . \I18n::POISITION_ON_MOON_MAP() . "</p>" ;
        
        $alt = ( $c->altitude - 96 )*255;
                
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::COORDINATE()       . " :</strong> " . $c->getGeoCoord() . "</li>" ;
        $res .= "<li><strong>" . \I18n::BIOME()            . " :</strong> " . $c->biome->getName() . " " . \I18n::HELP("/Help/Biome?id={$c->biome->id}") . "</li>" ;
        $res .= "<li><strong>" . \I18n::ALTITUDE()         . " :</strong> " . $alt . " ". \I18n::METERS() . "</li>";
        $res .= "<li><strong>" . \I18n::DAY_OR_NIGHT()     . " :</strong> " . ($c->sun < 0 ? \I18n::NIGHT() : \I18n::DAY()) . "</li>";
        $res .= "<li><strong>" . \I18n::MONSTERS()         . " :</strong> " . round($c->monster_out) . "</li>";
        $res .= "</ul>" ;
        
        parent::__construct(
                \I18n::GEOGRAPHY(),
                new \Quantyl\XML\Html\A("/Game/Map", \I18n::MAP()),
                "",
                $res,
                $classes) ;
    }
    
}
