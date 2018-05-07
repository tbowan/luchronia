<?php

namespace Form ;

class Feed implements \Quantyl\Form\Input {
    
    private $_char ;
    private $_qtties ;
    
    public function __construct(\Model\Game\Character $char) {
        $this->_char = $char ;
        $this->_qtties = array() ;
        foreach (\Model\Game\Ressource\Inventory::GetFromCharacter($char) as $inv) {
            if ($inv->isDrinkable() || $inv->isEatable()) {
                $this->_qtties[$inv->id] = array ($inv, 0) ;
            }
        }
    }

    private function addRow(\Model\Game\Ressource\Inventory $inv, $qtty, $key, \Quantyl\XML\Html\Table &$table) {
        
        $eu = 0 ;
        $hu = 0 ;
        
        $eatable    = \Model\Game\Ressource\Eatable::GetByItem($inv->item) ;
        $drinkable  = \Model\Game\Ressource\Drinkable::GetByItem($inv->item) ;
        
        if ($eatable != null && $eatable->canEat($this->_char)) {
            $eu += $eatable->energy ;
        } else if ($drinkable != null) {
            $hu += $drinkable->hydration ;
            if ($this->_char->race->equals(\Model\Enums\Race::HUMAN())) {
                $eu += $drinkable->energy ;
            }
        }
        
        
        $table->addRow(array(
            $inv->item->getImage("icone-inline") . " " . $inv->item->getName(),
            "<span id=\"$key-energy\">$eu</span>",
            "<span id=\"$key-hydration\">$hu</span>",
            "<span id=\"$key-remain\">{$inv->amount}</span>",
            "<input"
            . " class=\"feed_input\""
            . " type=\"text\""
            . " name=\"$key\""
            . " value=\"$qtty\""
            . " onchange=\"feed_change();\""
            . " onkeyup=\"this.onchange();\""
            . " onpaste=\"this.onchange();\""
            . " oninput=\"this.onchange();\""
            . " style=\"width:4em;min-width:0;\""
            . " />"
            . " <input type=\"button\"     value=\"-\" onclick=\"feed_decrement(this.form, '$key')\" style=\"min-width:2em;\"/>"
            . "<input type=\"button\"   value=\"+\" onclick=\"feed_increment(this.form, '$key')\" style=\"min-width:2em;\"/>"
            . " <input type=\"button\"   value=\"A-\" onclick=\"feed_auto_min(this.form, '$key')\" style=\"min-width:2em;\"/>"
            . "<input type=\"button\"   value=\"A+\" onclick=\"feed_auto_max(this.form, '$key')\" style=\"min-width:2em;\"/>"
            . " <input type=\"button\"   value=\"R\" onclick=\"feed_reset(this.form, '$key')\" style=\"min-width:2em;\"/>"
        )) ;
    }
    
    public function addFirst($ce, $ch, &$table) {
        $table->addRow(array(
            \I18n::BEFORE_FEED(),
            "<span id=\"base-energy\">$ce</span>",
            "<span id=\"base-hydration\">$ch</span>",
            "",
            ""
        )) ;
    }
    
    public function addLast($ce, $ch, &$table) {
        
        if ($ce < 750) {
            $class_e = "feed_below" ;
        } else if ($ce <= 1000) {
            $class_e = "feed_good" ;
        } else {
            $class_e = "feed_high" ;
        }
        
        if ($ch < 750) {
            $class_h = "feed_below" ;
        } else if ($ch <= 1000) {
            $class_h = "feed_good" ;
        } else {
            $class_h = "feed_high" ;
        }
        
        $table->addRow(array(
            \I18n::AFTER_FEED(),
            "<span id=\"total-energy\" class=\"{$class_e}\" >" . $this->_char->getEnergy() . "</span>",
            "<span id=\"total-hydration\" class=\"{$class_h}\" >" . $this->_char->getHydration() . "</span>",
            "",
            ""
        )) ;
    }
    
    public function getHTML($key = null) {
        
        $res = \I18n::FEED_MESSAGE() ;
        
        $res .= "<script src=\"/js/feed.js\"></script>" ;
        
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::ENERGY_ICO(),
            \I18n::HYDRATION_ICO(),
            \I18n::REMAIN(),
            \I18n::ACTIONS()
        )) ;
        
        $ce = $this->_char->getEnergy() ;
        $ch = $this->_char->getHydration() ;
        
        $this->addFirst($ce, $ch, $table) ;
        foreach ($this->_qtties as $id => $t) {
            $this->addRow($t[0], $t[1], "{$key}[{$id}]", $table) ;
        }
        $this->addLast($ce, $ch, $table) ;
        

        
        $res .= $table ;
        return $res ;
    }

    public function getValue() {
        return $this->_qtties ;
    }

    public function isValid() {
        foreach ($this->_qtties as $t) {
            if ($t[1] > $t[0]->amount) {
                return false ;
            }
        }
        return true ;
    }

    public function parseValue($value) {
        if (! is_array($value)) {
            return ;
        }
        foreach ($value as $id => $qtty) {
            $inv = $this->_qtties[$id][0] ;
            $this->_qtties[$id] = array($inv, floatval($qtty)) ;
        }
    }

    public function setValue($value) {
        
    }

}
