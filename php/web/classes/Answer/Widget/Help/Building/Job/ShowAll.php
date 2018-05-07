<?php

namespace Answer\Widget\Help\Building\Job ;

class ShowAll extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        $res = "" ;
        $res .= "<h2>" . \I18n::HELP_TITLE_ALLJOBS() . "</h2>" ;
        $res .= \I18n::ALLJOBS_MESSAGE() ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        
        $table->addHeaders(array(
            \I18n::BUILDING_JOB(),
            \I18n::MINISTRY(),
            \I18n::HEALTH(),
            \I18n::MAX_LEVEL()
        )) ;
        
        foreach (\Model\Game\Building\Job::GetAll() as $j) {
            $table->addRow(array(
                $j->getName() . " " . \I18n::HELP("/Help/Building/Job?id={$j->id}"),
                $j->ministry->getImage("icone-inline") . " " . \I18n::HELP("/Help/Poliotic/Ministry?id={$j->ministry->id}"),
                $j->health,
                $j->level
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }
    
}
