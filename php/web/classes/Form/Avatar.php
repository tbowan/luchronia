<?php

namespace Form ;

class Avatar implements \Quantyl\Form\Input {
    
    private $_race ;
    private $_sex ;
    
    private $_mandatory ;
    
    private $_error ;
    
    private $_fieldsets ;
    
    public function __construct($race = null, $sex = null, $mandatory = false) {
        $this->_race        = $race ;
        $this->_sex         = $sex ;
        $this->_mandatory   = $mandatory ;
        $this->_fieldsets   = array() ;
        $this->_error       = null ;
        
        foreach (\Model\Game\Avatar\Layer::getAll() as $layer) {
            if ($this->_race != null && $this->_sex != null) {
                $this->_fieldsets[$layer->getId()] = new Avatar\Item($layer, $this->_race, $this->_sex, $this->_mandatory) ;
            } else {
                $this->_fieldsets[$layer->getId()] = new \Quantyl\Form\Model\Id(\Model\Game\Avatar\Item::getBddTable(), "", $this->_mandatory) ;
            }
        }
    }
    
    public function isValid() {
        foreach ($this->_fieldsets as $fs) {
            if (! $fs->isValid()) {
                return false ;
            }
        }
        return true ;
    }
    
    public function getLayer($key) {
        
        $res  = "<fieldset>" ;
        $res .= "<legend>" . \I18n::LAYER() . "</legend>" ;
        
        foreach (\Model\Game\Avatar\Layer::GetAll() as $layer) {
            $id = $layer->getId() ;
            
            $res .= "<input"
                    . " class=\"avatar-layer-radio\""
                    . " id=\"avatar-layer-radio-$id\""
                    . " type=\"radio\""
                    . " name=\"{$key}[layer]\""
                    . " value=\"$id\""
                    . " style=\"display:none;\""
                    . " onchange=\"avatar_layer_change(this)\""
                    . "/>" ;
            
            $res .= "<label"
                    . " for=\"avatar-layer-radio-$id\""
                    . " style=\"float: left;\""
                    . ">" ;
            $img = $layer->getImage("avatar-layer-icone") ;
            $img->setAttribute("id", "avatar-layer-img-$id") ;
            $res .= $img ;
            $res .= "</label>" ;
            
        }
        
        $res .= "<div class=\"clear\"></div>" ;
        $res .= "</fieldset>" ;
        return $res ;
        
    }

    public function getHTML($key = null) {
        $res = "<div id=\"form-avatar\" >" ;
        
        if ($this->_error !== null) {
            $res .= "\t<div class=\"error\">\n"
                    . "\t" . $this->_error
                    . "\n\t</div>\n" ;
        }
        
        $res .= "<script src=\"/js/avatar.js\"></script>" ;
        $res .= "<div class=\"avatar-selects\"" ;
        $res .= " style=\"float: left; width: 50%;\"" ;
        // $res .= " onload=\"init_avatar() ;\"" ;
        $res .= " >" ;
        
        $res .= $this->getLayer($key) ;
        
        foreach ($this->_fieldsets as $id => $f) {
            $res .= $f->getHTML("{$key}[$id]") ;
        }
        $res .= "</div>" ;
        
        $res .= "<div class=\"avatar-example\" style=\"float: left; width: 50%;\">" ;
        
        $svg = new \Quantyl\XML\SVG\SVG(400, 640) ;
        foreach (\Model\Game\Avatar\Layer::GetAll() as $l) {
            
            $v = $this->_fieldsets[$l->getId()]->getValue() ;
            $url = ($v == null ? "" : $v->getImgPath()) ;
            $img = $svg->addChild(new \Quantyl\XML\SVG\Image($url, 0, 0, 400, 640)) ;
            $img->setAttribute("id", "avatar-svg-layer-" . $l->getId()) ;
        }
        $res .= $svg ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"clear\"></div>" ;
        $res .= "</div>" ;
        return $res ;
    }

    public function getValue() {
        $items = array() ;
        foreach ($this->_fieldsets as $id => $fs) {
            $items[$id] = $fs->getValue() ;
        }
        return $items ;
    }

    public function parseValue($value) {
        $this->_error = "" ;
        foreach ($this->_fieldsets as $id => $fs) {
            $v = (isset($value[$id]) ? $value[$id] : null) ;
            try {
                $fs->parseValue($v) ;
            } catch (\Exception $ex) {
                $this->_error .= $ex->getMessage() ;
            }
        }
        if ($this->_error != "") {
            throw new \Exception() ;
        }
        
    }

    public function setValue($value) {
        foreach ($this->_fieldsets as $id => $fs) {
            if (isset($value[$id])) {
                $fs->setValue($value[$id]) ;
            } else {
                $fs->setValue(null) ;
            }
        }
    }

}
