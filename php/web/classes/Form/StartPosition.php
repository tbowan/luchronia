<?php

namespace Form ;

use Model\Game\Building\Instance;
use Model\Game\Building\Job;

class StartPosition implements \Quantyl\Form\Input {
    
    private $_mandatory ;
    
    private $_cities ;
    private $_city   ;
    
    
    public function __construct($mandatory = false) {
        $this->_mandatory = $mandatory ;
        $this->initCities() ;
    }
    
    public function initCities() {
        $this->_cities = array() ;
        
        $startjobs = array (
            "TownHall"
        ) ;
        
        foreach ($startjobs as $jobname) {
            $job = Job::GetByName($jobname) ;
            foreach (Instance::GetFromJob($job) as $instance) {
                $city = $instance->city ;
                $this->_cities[$city->id] = $city ;
            }
        }
    }
    
    public function getValue() {
        return $this->_city ;
    }

    public function isValid() {
        return $this->_city !== null || ! $this->_mandatory ;
    }
    
    public function parseValue($value) {
        if ($value != null) {
            if (isset($this->_cities[$value])) {
                $this->_city = $this->_cities[$value] ;
            } else {
                $this->_city = null ;
            }
        } else {
            $this->_city = null ;
        }
    }

    public function setValue($value) {
        if ($value != null && $this->_cities[$value->getId()]) {
            $this->_city = $value ;
        }
        
    }

    private function getHTMLCities($key) {
        $res  = "<fieldset class=\"startpos_cities\" ><legend>" . \I18n::STARTPOS_CITIES() . "</legend>" ;
        
        $res .= "<select"
                . " id=\"startpos_select\""
                . " name=\"$key\""
                . " onchange=\"startpos_select_change(this)\""
                . ">" ;
        
        foreach ($this->_cities as $c) {
            if ($c->equals($this->_city)) {
                $sel = "selected=\"\" " ;
            } else {
                $sel = "" ;
            }
            $res .= "<option value=\"{$c->id}\" $sel>" . $c->getName() . "</option>" ;
        }
        
        $res .= "</select>" ;
        $res .= "</fieldset>" ;
        return $res ;
    }
    
    private function addLines($svg, $city) {
        if ($city != null) {
            $cx = 720 + $city->longitude * 4 ;
            $cy = 360 - $city->latitude * 4 ;
        }else {
            $cx = -1 ;
            $cy = -1 ;
        }
            
        $abs = $svg->addChild(new \Quantyl\XML\SVG\Line($cx, 0, $cx, 720)) ;
        $abs->setAttribute("id", "startpos_abs") ;
        $abs->setAttribute("class", "startpos_line") ;
        
        $ord = $svg->addChild(new \Quantyl\XML\SVG\Line(0, $cy, 1440, $cy)) ;
        $ord->setAttribute("id", "startpos_ord") ;
        $ord->setAttribute("class", "startpos_line") ;
    }
    
    public function addCities($svg) {
        foreach ($this->_cities as $c) {
            $cx = 720 + $c->longitude * 4 ;
            $cy = 360 - $c->latitude * 4 ;
            $circle = $svg->addChild(new \Quantyl\XML\SVG\Circle($cx, $cy, 10)) ;
            $circle->setAttribute("id", "startpos_city_{$c->id}") ;
            $circle->setAttribute("class", "startpos_city") ;
            $circle->setAttribute("onclick", "startpos_city_click(this)") ;
        }
    }
    
    public function getHTMLMap($key) {
        $res = "<fieldset class=\"startpos_map\" ><legend>" . \I18n::STARTPOS_MAP() . "</legend>" ;
        
        $svg = new \Quantyl\XML\SVG\SVG(720, 360) ;
        $svg->setViewPort(0, 0, 1440, 720) ;
        $svg->addChild(new \Quantyl\XML\SVG\Image("/Media/Maps/Moonmap.png", 0, 0, 1440, 720)) ;
        $svg->addChild(new \Quantyl\XML\SVG\Image("/Media/Maps/daynight.png", 0, 0, 1440, 720)) ;
        
        $this->addLines($svg, $this->_city) ;
        
        $this->addCities($svg) ;
        
        $res .= $svg ;
        $res .= "</fieldset>" ;
        return $res ;
    }
    
    public function getHTML($key = null) {
        
        $res  = "<div id=\"form_startpos\">" ;
        $res .= "<script src=\"/js/startpos.js\"></script>" ;
        $res .= $this->getHTMLCities($key) ;
        $res .= $this->getHTMLMap($key) ;
        $res .= "<div class=\"clear\"></div>" ;
        $res .= "</div>" ;
        return $res ;
    }


}