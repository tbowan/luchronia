<?php

namespace Answer\Widget\Game\Building\Outside ;

class Prospection extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\City $city, \Model\Game\Character $me, $classes = "") {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::RESSOURCE(),
            \I18n::WHEN(),
            \I18n::COEF()
        )) ;
        
        foreach (\Model\Game\Ressource\Prospection::GetFromCityAndCharacter($city, $me) as $pro) {
            $table->addRow(array(
                $pro->item->getImage("icone") . " " . $pro->item->getName(),
                \I18n::_date_time($pro->when - DT),
                number_format($pro->coef, 2)
            )) ;
        }
        
        if ($table->getRowsCount() >0) {
            $msg = $table ;
        } else {
            $msg = \I18n::NO_PROSPECTION_FOUND() ;
        }
        
        parent::__construct(\I18n::PROSPECTION(), "", "", "$msg", "") ;
        
    }
    
}
