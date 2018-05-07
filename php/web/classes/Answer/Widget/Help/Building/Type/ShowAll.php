<?php

namespace Answer\Widget\Help\Building\Type ;

class ShowAll extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        
        $res = "" ;
        $res .= "<h2>" . \I18n::HELP_TITLE_ALLTYPE() . "</h2>" ;
        $res .= \I18n::ALLTYPES_MESSAGE() ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        
        $table->addHeaders(array(
            \I18n::BUILDING_TYPE(),
            \I18n::BUILDING_WEAR(),
            \I18n::BUILDING_TECH(),
        )) ;
        
        foreach (\Model\Game\Building\Type::GetAll() as $t) {
            $table->addRow(array(
                $t->getName() . " " . \I18n::HELP("/Help/Building/Type?id={$t->id}"),
                $t->wear,
                $t->technology
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }
    
}
