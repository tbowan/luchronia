<?php

namespace Answer\Widget\Game\Building\Outside ;

class Description extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Building\Job $job, $classes = "") {
        
        
        $res  = "" ;
        $res .= new \Quantyl\XML\Html\Img("/Media/icones/Model/Building/OutSide.png", $job->getName(), "card-1-3 left") ;
        $res .= $job->getDescription() ;
        $res .= new \Quantyl\XML\Html\A("/Help/Building/Job?id={this->_job->id}", \I18n::KNOW_MORE()) ;
        
        parent::__construct(
                \I18n::DESCRIPTION(),
                "",
                "",
                $res,
                $classes);
       
    }
    
}
