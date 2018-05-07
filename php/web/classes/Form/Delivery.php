<?php

namespace Form ;

class Delivery implements \Quantyl\Form\Input {
    
    private $_max ;
    
    private $_items ;
    private $_precision ;
    private $_cost ;
    
    private $_label ;
    
    public function __construct($max = 0, $label = "") {
        $this->_max = $max ;
        $this->_items = array() ;
        $this->_precision = 0.0 ;
        $this->_cost = 0 ;
        $this->_label = $label ;
    }

    public function itemSelect($key) {
        $res = "<select id=\"$key-item\" name=\"$key\">" ;
        foreach (\Model\Game\Ressource\Item::GetAll() as $i) {
            if ($i->prestige > 0) {
                $res .= "<option value=\"{$i->id}\">" . $i->getName() . "</option>" ;
            }
        }
        $res .= "</select>" ;
        return $res ;
    }
    
    private function getItemTable($key) {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::RESSOURCE(),
            \I18n::AMOUNT(),
            \I18n::COST_U(),
            \I18n::COST(),
            \I18n::ACT()
        )) ;
        $table->setAttribute("id", "$key-table") ;
        
        $table->addRow(array(
            $this->itemSelect($key), "", "", "", "<input type=\"button\" value=\"+\" onclick=\"delivery_add('$key')\" style=\"min-width:2em;\" />"
        )) ;
        
        foreach ($this->_items as $idx => $cmd) {
            $i = $cmd["item"] ;
            $q = $cmd["qtty"] ;
            $row = $table->addRow(array(
                $i->getImage("icone") . " " . $i->getName() .
                "<input type=\"hidden\" name=\"{$key}[items][{$idx}][item]\" value=\"{$i->id}\" />",
                "<input type=\"text\""
                        . " name=\"{$key}[items][{$idx}][qtty]\""
                        . " value=\"$q\""
                        . " onchange=\"delivery_change(this, '$key');\""
                        . " onkeyup=\"this.onchange();\""
                        . " onpaste=\"this.onchange();\""
                        . " oninput=\"this.onchange();\""
                        . " style=\"width:4em;min-width:0;\""
                        . " />",
                                
                "<span id=\"delivery-prestige-{$idx}\">" . $i->prestige . "</span>",
                "<span id=\"delivery-cost-{$idx}\">" . ($i->prestige * $q) . "</span>",
                "<input type=\"button\" value=\"-\" onclick=\"delivery_del(this);\" style=\"min-width:2em;\" />"
            )) ;
            $row->setAttribute("id", $idx) ;
        }
        return $table ;
    }
    
    private function getCostTable($key) {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addRow(array(
            \I18n::SUBTOTAL(),
            "<span id=\"delivery-subtotal-$key\">" . $this->_cost . "</span>"
        )) ;
        $table->addRow(array(
            \I18n::PRECISION(),
            "<input id=\"delivery-precision-$key\" type=\"text\" name=\"{$key}[precision]\" value=\"{$this->_precision}\" />"
            )) ;
        $table->addRow(array(
            \I18n::TOTAL(),
            "<span id=\"delivery-total-$key\">" . ($this->_cost * (1.0 + $this->_precision / 100)) . "</span>"
        )) ;
        
        return $table ;
        
    }
    
    public function getHTML($key = null) {
        $res = "" ;
        
        if ($this->_label != "") {
            $res = "<fieldset><legend>" . $this->_label . "</legend>" ;
        }
        
        $res .= "<script src=\"/js/delivery.js\"></script>" ;
        
        $res .= $this->getItemTable($key) ;
        $res .= $this->getCostTable($key) ;
        
        
        
        if ($this->_label != "") {
            $res .= "</fieldset>" ;
        }
        
        return $res ;
    }

    public function getValue() {
        return array (
            "items"     => $this->_items,
            "precision" => $this->_precision / 100.0
                ) ;
    }

    public function isValid() {
        return $this->_max == 0 || $this->_cost * (1.0 + $this->_precision / 100) <= $this->_max ;
    }

    public function parseValue($value) {
        if (! is_array($value)) {
            return ;
        }
        
        $this->_items = array() ;
        $this->_cost = 0 ;
        foreach ($value["items"] as $cmd) {
            $i = \Model\Game\Ressource\Item::GetById($cmd["item"]) ;
            if ($i != null && $i->prestige > 0) {
                $this->_items[] = array ("item" => $i, "qtty" => $cmd["qtty"]) ;
                $this->_cost += $cmd["qtty"] * $i->prestige ;
            }
        }
        
        $this->_precision = $value["precision"] ;        
    }

    public function setValue($value) {
        $this->_precision = $value["precision"] * 100 ;
        $this->_items = $value["items"] ;
        $this->_cost = 0 ;
        foreach ($this->_items as $cmd) {
            $this->_cost += $cmd["item"]->prestige * $cmd["qtty"] ;
        }
    }

}
