<?php

namespace Answer\View\BackOffice ;

class Stats extends \Answer\View\Base {
    
    public function getTplContent() {
        
        return ""
                . new \Widget\BackOffice\Stats\Scripts(\I18n::BACKOFFICE_STATS_RUNNING(), \Model\Stats\Script::GetRunning(), "")
                . new \Widget\BackOffice\Stats\Scripts(\I18n::BACKOFFICE_STATS_LASTRUN(), \Model\Stats\Script::GetLastRun(), "")
                ;
        
    }

}
