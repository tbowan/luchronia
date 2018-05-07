<?php

namespace Widget\BackOffice\Stats ;

class Scripts extends \Answer\Widget\Misc\Section {
    
    public function __construct($head = null, $scripts, $classes = "") {
        parent::__construct($head, "", "", $this->getTable($scripts) , $classes);
    }
    
    public function getTable($scripts) {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::HOSTNAME(),
            \I18n::SCRIPT(),
            \I18n::PID(),
            \I18n::START(),
            \I18n::PERCENT(),
            \I18n::END(),
            \I18n::ACTIONS(),
        )) ;
        
        foreach ($scripts as $s) {
            $table->addRow(array(
                new \Quantyl\XML\Html\A("/BackOffice/Stats/Host?hostname={$s->hostname}", $s->hostname),
                new \Quantyl\XML\Html\A("/BackOffice/Stats/Script?script={$s->script}", $s->script),
                new \Quantyl\XML\Html\A("/BackOffice/Stats/Execution?id={$s->id}", $s->pid) . " " . ($s->isUp() ? \I18n::YES_ICO() : \I18n::NO_ICO()),
                \I18n::_date_time($s->start),
                new \Quantyl\XML\Html\Meter(0, 100, $s->percent),
                \I18n::_date_time($s->getEnd()),
                new \Quantyl\XML\Html\A("/BackOffice/Stats/KillScript?id={$s->id}", \I18n::DELETE())
            )) ;
        }
        return $table ;
    }
    
    public function getStats() {
        $res = "<h2>" . \I18n::BACKOFFICE_STATS_SCRIPTS() . "</h2>" ;
        
        // Running scripts
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::HOSTNAME(),
            \I18n::SCRIPT(),
            \I18n::LAST_OK(),
        )) ;
        
        foreach (\Model\Stats\Script::GetHostAndScripts() as $row) {
            list($script, $hostname) = $row ;
            
            $last_ok = \Model\Stats\Script::GetLastDone($hostname, $script) ;
            
            $table->addRow(array(
                $hostname,
                $script,
                ($last_ok === null ? "-" : \I18n::_date_time($last_ok->end))
            )) ;
        }
        
        $res .= $table ;
        
        return $res ;
    }

}
