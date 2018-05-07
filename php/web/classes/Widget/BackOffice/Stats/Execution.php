<?php

namespace Widget\BackOffice\Stats ;

class Execution extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Stats\Script $s, $classes = "") {
        $res = "<ul>" ;
        $res .= "<li><strong>" . \I18n::HOSTNAME() . " : </strong>" . new \Quantyl\XML\Html\A("/BackOffice/Stats/Host?hostname={$s->hostname}", $s->hostname) . "</li>";
        $res .= "<li><strong>" . \I18n::SCRIPT() . " : </strong>" . new \Quantyl\XML\Html\A("/BackOffice/Stats/Script?script={$s->script}", $s->script) . "</li>";
        $res .= "<li><strong>" . \I18n::PID() . " : </strong>" . $s->pid . " " . ($s->isUp() ? \I18n::YES_ICO() : \I18n::NO_ICO()) . "</li>";
        $res .= "<li><strong>" . \I18n::START() . " : </strong>" . \I18n::_date_time($s->start) . "</li>";
        $res .= "<li><strong>" . \I18n::END() . " : </strong>" . \I18n::_date_time($s->getEnd()) . "</li>";
        $res .= "<li><strong>" . \I18n::PROGRESS() . " : </strong>" . new \Quantyl\XML\Html\Meter(0, 100, $s->percent) . "</li>";
        $res .= "</ul>" ;
        
        parent::__construct(\I18n::SCRIPT_EXECUTION(), new \Quantyl\XML\Html\A("/BackOffice/Stats/KillScript?id={$s->id}", \I18n::DELETE()),"", $res, $classes);
    }
    
}
