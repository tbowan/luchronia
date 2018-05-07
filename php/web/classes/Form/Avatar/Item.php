<?php

namespace Form\Avatar ;

class Item implements \Quantyl\Form\Input {

    private $_layer ;
    private $_race ;
    private $_sex ;
    private $_mandatory ;
    
    private $_items ;
    
    private $_item ;
    
    public function __construct(
            \Model\Game\Avatar\Layer $l,
            \Model\Enums\Race $r,
            \Model\Enums\Sex $s,
            $mandatory = false
                    ) {
        
        $this->_race        = $r ;
        $this->_sex         = $s ;
        $this->_layer       = $l ;
        $this->_mandatory   = $mandatory ;
        
        $this->initItems() ;
    }

    public function initItems() {
        $this->_items = array() ;
        $ids = array() ;
        
        foreach (\Model\Game\Avatar\Item::GetFiltered($this->_race, $this->_sex, $this->_layer) as $i) {
            if ($i->price == 0) {
                $this->_items[$i->getId()] = $i ;
                $ids[] = $i->getId() ;
            }
        }
        $id = array_rand($ids) ;
        if ($id !== null) {
            $this->_item = $this->_items[$ids[$id]] ;
        }
    }
    
    public function getHTML($key = null) {
        
        $lid = $this->_layer->getId() ;
        
        $res = "<fieldset"
                . " id=\"fieldset-layer-$lid\""
                . " class=\"avatar-fieldset\""
                . " style=\"display: none;\" >" ;
        $res .= "<legend>" . $this->_layer->getName() . "</legend>" ;
        
        foreach ($this->_items as $id => $item) {
            $res .= "<input"
                    . " class=\"avatar-item-$lid\""
                    . " id=\"avatar-item-$id\""
                    . " type=\"radio\""
                    . " name=\"$key\""
                    . " value=\"$id\""
                    . " style=\"display:none;\""
                    . " onchange=\"avatar_item_change(this)\"" ;
            if ($this->_item != null && $id == $this->_item->getId()) {
                $res .= " checked=\"\"" ;
            }
            $res .= "/>" ;
            
            $res .= "<label"
                    . " for=\"avatar-item-$id\""
                    . " style=\"float: left;\""
                    . ">" ;
            $img = $item->getThumbImage("avatar-item-icone") ;
            $img->setAttribute("id", "avatar-item-img-$id") ;
            $res .= $img ;
            $res .= "</label>" ;
            
        }
        $res .= "<div class=\"clear\"></div>" ;
        $res .= "</fieldset>" ;
        return $res ;
    }

    public function isValid() {
        return $this->_item !== null || count($this->_items) == 0 || ! $this->_mandatory ;
    }

    public function getValue() {
        return $this->_item ;
    }
    
    public function parseValue($value) {
        if ($value != null) {
            if (isset($this->_items[$value])) {
                $this->_item = $this->_items[$value] ;
            } else {
                $this->_item = null ;
            }
        } else {
            $this->_item = null ;
        }
    }

    public function setValue($value) {
        if ($value != null && $this->_items[$value->getId()]) {
            $this->_item = $value ;
        }
        
    }

}
