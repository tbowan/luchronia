<?php

namespace Answer\Widget\Game\Building\Outside ;

class Information extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\City $city, \Model\Game\Building\Job $job, $classes = "") {
        
        $res  = "" ;
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::NAME() . " :</strong> " . $job->getName() . " </li>" ;
        $res .= "<li><strong>" . \I18n::POSITION() . " :</strong> " . new \Quantyl\XML\Html\A("/Game/City?id={$city->id}", $city->getName()) . " </li>" ;
        $res .= "<li><strong>" . \I18n::MONSTERS()  . " :</strong> " . number_format($city->monster_out) . " / " . (20 * $city->albedo) . "</li>" ;
        $res .= "</ul>" ;
        
        parent::__construct(
                \I18n::INFORMATIONS(),
                "",
                "",
                $res,
                "");
       
    }
    
}
