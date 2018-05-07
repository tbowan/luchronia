<?php

namespace Form ;

use Quantyl\Form\Model\Select;

class ItemSelect extends Select {
    
    public function __construct($label = null, $mandatory = false, $description = null) {
        parent::__construct(\Model\Game\Ressource\Item::getBddTable(), $label, $mandatory, $description) ;
    }
    
    
    
}
