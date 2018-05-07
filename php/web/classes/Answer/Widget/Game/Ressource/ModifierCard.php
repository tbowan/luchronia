<?php

namespace Answer\Widget\Game\Ressource ;

class ModifierCard extends \Answer\Widget\Misc\Card {

    public function __construct(\Model\Game\Modifier $modifier) {
        
        $content = "" ;
        $content .= $this->_getDuration($modifier) ;
        $content .= $this->_getValues($modifier) ;
        $content .= $this->_getCharacteristics($modifier) ;
        
        parent::__construct($modifier->getName(), $content);
    }

    private function _getValues(\Model\Game\Modifier $modifier) {
        $res = "" ;
        
        if ($modifier->time != 0) {
            $res .= "<li><strong>" . \I18n::TIME() . " : </strong> {$modifier->time}</li>" ;
        }
        if ($modifier->experience != 0) {
            $res .= "<li><strong>" . \I18n::EXPERIENCE() . " : </strong> {$modifier->experience}</li>" ;
        }
        if ($modifier->level != 0) {
            $res .= "<li><strong>" . \I18n::EXPERIENCE() . " : </strong> {$modifier->level}</li>" ;
        }
        if ($modifier->energy != 0) {
            $res .= "<li><strong>" . \I18n::ENERGY() . " : </strong> {$modifier->energy}</li>" ;
        }
        if ($modifier->hydration != 0) {
            $res .= "<li><strong>" . \I18n::HYDRATION() . " : </strong> {$modifier->hydration}</li>" ;
        }
        if ($modifier->health != 0) {
            $res .= "<li><strong>" . \I18n::HEALTH() . " : </strong> {$modifier->health}</li>" ;
        }
        if ($modifier->position != 0) {
            $res .= "<li><strong>" . \I18n::POSITION() . " : </strong> " ;
            $res .= new \Quantyl\XML\Html\A("/Game/City?id={$modifier->position->id}", $modifier->position->getName()) ;
            $res .= "</li>" ;
        }

        if ($res != "") {
            $msg = "<h4>" . \I18n::MODIFIER_VALUES() . "</h4>" ;
            $msg .= "<ul>$res</ul>" ;
            return $msg ;
        } else {
            return "" ;
        }
    }
    
    private function _getCharacteristics(\Model\Game\Modifier $modifier) {
        
        $res = "" ;
        
        foreach (\Model\Game\Characteristic\Modifier::GetByModifier($modifier) as $char) {
            $carac = $char->characteristic ;
            
            $res .= "<li><strong>" . $carac->getImage("icone-inline") . " " . $carac->getName() . " : </strong>" . $char->bonus . "</li>" ;
        }
        
        if ($res != "") {
            $msg = "<h4>" . \I18n::MODIFIER_CHARACTERISTICS() . "</h4>" ;
            $msg .= "<ul>$res</ul>" ;
            return $msg ;
        } else {
            return "" ;
        }
    }
    
    public function _getDuration(\Model\Game\Modifier $modifier) {
        if ($modifier->duration == -1) {
            $msg = \I18n::MODIFIER_DURATION_INFINITE() ;
        } else if ($modifier->duration == 0) {
            $msg = \I18n::MODIFIER_DURATION_IMMEDIATE() ;
        } else {
            $msg = \I18n::MODIFIER_DURATION_UNTIL($modifier->duration, \I18n::_date_time(time() + $modifier->duration - DT)) ;
        }
        return $msg ;
    }
    
}
