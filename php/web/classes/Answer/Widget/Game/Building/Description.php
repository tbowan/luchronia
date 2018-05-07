<?php

namespace Answer\Widget\Game\Building ;

class Description extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Instance $instance, $classes = "") {
        $res  = "" ;
        $res .= $instance->getImage("left card-1-3") ;
        $res .= $instance->job->getDescription() ;
        $res .= new \Quantyl\XML\Html\A("/Help/Building/Job?id={$instance->job->id}", \I18n::KNOW_MORE()) ;
        $res .= $instance->type->getDescription() ;
        $res .= new \Quantyl\XML\Html\A("/Help/Building/Type?id={$instance->type->id}", \I18n::KNOW_MORE()) ;
        
        parent::__construct(
                \I18n::DESCRIPTION(),
                "",
                "",
                $res,
                $classes);
    }
    
}
