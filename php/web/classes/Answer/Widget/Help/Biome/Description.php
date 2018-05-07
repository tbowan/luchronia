<?php

namespace Answer\Widget\Help\Biome;

class Description extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Biome $b, $classes = "") {
        
        $res = "";
        $res .= "<div class=\"card-1-3\">" ;
        $res .= $b->getImage("full") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"card-2-3\">" ;
        $res .= $b->getDescription() ;
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::TEMPERATURE() . " :</strong> " . $b->getTemperature() . "</li>" ;
        $res .= "<li><strong>" . \I18n::ALBEDO() . " :</strong> [" .$b->kmin . ", " . $b->kmax . "] </li>" ;
        $res .= "</ul>" ;
        $res .= "</div>" ;
        
        $more = new \Quantyl\XML\Html\A("/Help/Biome", \I18n::BIOME_ALL()) ;
        
        parent::__construct(\I18n::DESCRIPTION(), $more, "", $res, $classes);
    }

}

        