<?php

namespace Answer\Widget\Help\Characteristic;

class Description extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Characteristic $c, $viewer, $classes = "") {
        
        $msg = "<div class=\"card-1-3\">" ;
        $msg .= $c->getImage() ;
        $msg .= "</div>" ;
        
        $msg .= "<div class=\"card-2-3\">" ;
        $msg .= $c->getDescription() ;
        $msg .= $this->getSecondary($c) ;
        $msg .= "</div>" ;
        
        $more = new \Quantyl\XML\Html\A("/Help/Characteristic", \I18n::CHARACTERISTIC_ALL()) ;
        
        parent::__construct(\I18n::DESCRIPTION(), $more, "", $msg, $classes);
    }
    
    public function getSecondary(\Model\Game\Characteristic $c) {
        if ($c->primary == 0) {
            $res = "<h2>" . \I18n::SECONDARY_CHARACTERISTIC() . "</h2>" ;
            $res .= \I18n::SECONDARY_CHARACTERISTIC_DETAIL_MSG() ;
            $res .= "<ul>" ;
            foreach (\Model\Game\Characteristic\Secondary::getFromSecondary($c) as $b) {
                $base = $b->base ;
                $res .= "<li>" . $base->getImage("icone-inline") . " " . $base->getName() . \I18n::Help("/Help/Characteristic?id={$b->id}") . "</li>" ;
            }
            $res .= "</ul>" ;
            return $res ;
        } else {
            return "" ;
        }
    }

}
