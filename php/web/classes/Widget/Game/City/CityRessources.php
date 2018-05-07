<?php

namespace Widget\Game\City ;

class CityRessources extends \Quantyl\Answer\Widget {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $city) {
        $this->_city = $city ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::NATURAL_RESSOURCES() . "</h2>";
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(\I18n::ITEM(), \I18n::COEF())) ;
        foreach (\Model\Game\Ressource\Natural::GetFromCity($this->_city) as $nat) {
            $table->addRow(array(
                $nat->item->getImage("icone-med") . " " . $nat->item->getName(),
                number_format($nat->coef, 2)
            )) ;
        }
        $res .= $table ;
        
        return "$res" ;
        
    }
    
    
}
