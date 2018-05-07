<?php

namespace Answer\Widget\Help\Building\Type ;

class ShowOne extends \Quantyl\Answer\Widget {
    
    private $_type ;
    
    public function __construct(\Model\Game\Building\Type $t) {
        $this->_type = $t ;
    }
    
    public function getContent() {
        $res  = "<h2>" . \I18n::DESCRIPTION() . "</h2>" ;
        $res .= \I18n::HELP_BUILDING_TYPE_MESSAGE() ;
        $res .= $this->_type->getDescription() ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_WEAR() . " : </strong> " . $this->_type->wear . "</li>" ;
        $res .= "<li><strong>" . \I18n::BUILDING_TECH() . " : </strong> " . $this->_type->technology . "</li>" ;
        $res .= "</ul>" ;
        
        return $res ;
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP($this->_type->getName()) ;
    }
    
}
