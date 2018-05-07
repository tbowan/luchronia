<?php

namespace Form\Post ;

class Goods implements \Quantyl\Form\Input {
    
    private $_char ;
    private $_qtties ;
    
    public function __construct(\Model\Game\Character $char) {
        $this->_char = $char ;
        $this->_qtties = array() ;
        foreach (\Model\Game\Ressource\Inventory::GetFromCharacter($char) as $inv) {
            $this->_qtties[$inv->id] = array ($inv, 0) ;
        }
        
    }

    private function addRow(\Model\Game\Ressource\Inventory $inv, $qtty, $key, \Quantyl\XML\Html\Table &$table) {
        
        $table->addRow(array(
            $inv->item->getImage("icone") . " " . $inv->item->getName(),
            $inv->amount,
            "<input type=\"text\" name=\"$key\" value=\"$qtty\" />"
        )) ;
    }
    
    public function getHTML($key = null) {
        
        $res = "<fieldset><legend>" . \I18n::INVENTORY() . "</legend>" ;
        
        $res .= \I18n::FORM_GOOD_MESSAGE() ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ITEM(),
            \I18n::QUANTITY_AVAILABLE(),
            \I18n::QUANTITY_CHOSEN()
        )) ;
        
        foreach ($this->_qtties as $id => $t) {
            $this->addRow($t[0], $t[1], "{$key}[{$id}]", $table) ;
        }
        
        $res .= $table ;
        
        $res .= "</fieldset>" ;
        
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
