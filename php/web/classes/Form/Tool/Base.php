<?php

namespace Form\Tool ;

class Base implements \Quantyl\Form\Input {
    
    protected $_tax ;
    protected $_cs ;
    
    protected $_choices   ;
    protected $_choice    ;
    protected $_chosenkey ;
    
    public function __construct(\Model\Game\Skill\Character $cs, \Model\Game\Tax\Complete $tax) {
        
        $this->_tax       = $tax ;
        $this->_cs        = $cs ;
        $this->_choice    = null ;
        $this->_chosenkey = null ;
        $this->initChoices($cs) ;
        
    }

    public function initChoicesTool(\Model\Game\Skill\Character $cs, \Model\Game\Skill\Tool $tool) {
        foreach (\Model\Game\Ressource\Inventory::GetFromItem($cs->character, $tool->item, 0.01) as $inv_1) {
            $need_munitions = false ;
            foreach (\Model\Game\Ressource\Munition::GetByWeapon($tool) as $munition) {
                $need_munitions = true ;
                foreach (\Model\Game\Ressource\Inventory::GetFromItem($cs->character, $munition->item, $munition->amount) as $inv_2) { ;
                    $this->_choices["{$inv_1->id}-{$inv_2->id}"] = array ($inv_1, $inv_2, $tool, $munition) ;
                }
            }
            if (! $need_munitions) {
                $this->_choices["{$inv_1->id}-"] = array ($inv_1, null, $tool, null) ;
            }
        }
    }
    
    public function initChoices(\Model\Game\Skill\Character $cs) {
        // Get the tools
        $this->_choices = array() ;
        if ($this->_cs->skill->by_hand != 0) {
            $this->_choices["-"] = array(null, null, null, null) ;
        }
        foreach (\Model\Game\Skill\Tool::GetFromSkill($cs->skill) as $tool) {
            $this->initChoicesTool($cs, $tool) ;
        }
        
    }
    
    public function getTime($tool) {
        $base = $this->_cs->getTimeCost() ;
        $coef = ($tool == null ? $this->_cs->skill->by_hand : $tool->getCoef()) ;
        return round($base / $coef, 2) ;
    }
    
    public function getAmount($munition) {
        return ($munition == null ? 1.0 : 1.0 + $munition->getCoef()) ;
    }
    
    public function getTaxableAmount($munition) {
        return $this->getAmount($munition) ;
    }
    
    public function getTitleTool() {
        return \I18n::TOOL() ;
    }
    
    public function getTitleMunition() {
        return \I18n::MUNITION() ;
    }
    
    public function getTitleGain() {
        return \I18n::GAIN() ;
    }

    
    
    public function getHTML($key = null) {
        
        $max_pay = - $this->_cs->character->position->credits ;
        
        $res = "<h2>" . \I18n::TOOL() . "</h2>" ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            "",
            $this->getTitleTool(),
            $this->getTitleMunition(),
            $this->getTitleGain(),
            \I18n::TIME(),
            \I18n::CREDITS()
        )) ;
        
        foreach ($this->_choices as $ids => $comp) {
            list($i1, $i2, $t, $m) = $comp ;
            
            if ($t == null) {
                // By hand
                $tool = $this->_cs->skill->getImage("icone-med") . \I18n::BY_HAND() ;
            } else {
                $tool = $i1->item->getImage("icone-med") . $i1->item->getName() . "<br/>" . \I18n::REMAIN() . " : " . $i1->amount ;
            }
            
            if ($m == null) {
                // By hand
                $muni = "-" ;
            } else {
                $muni = $i2->item->getImage("icone-med") . $i2->item->getName() . "<br/>" . \I18n::REMAIN() . " : " . $i2->amount ;
            }
            
            if ($ids == $this->_chosenkey) {
                $selected = "checked=\"\"" ;
            } else {
                $selected = "" ;
            }
            
            $amount = $this->getAmount($m) ;
            $taxable = $this->getTaxableAmount($m) ;
            
            $table->addRow(array(
                "<input type=\"radio\" name=\"$key\" value=\"$ids\" id=\"$key-$ids\" $selected/>",
                "<label for=\"$key-$ids\">$tool</label>",
                "<label for=\"$key-$ids\">$muni</label>",
                $this->displayAmount($amount),
                $this->getTime($t),
                max($max_pay, $this->_tax->getTax($taxable))
            )) ;
        }
        
        
        if ($table->getRowsCount() == 0) {
            $res .= \I18n::FORM_TOOL_NONE_FOUND($this->_cs->skill->id, $this->_cs->skill->getName()) ;
        } else {
            $res .= $table ;
        }
        return $res ;
        
    }
    
    public function displayAmount($amount) {
        return $amount ;
    }

    public function getValue() {
        return $this->_choice ;
    }

    public function isValid() {
        return $this->_choice != null ;
    }

    public function parseValue($value) {
        if (isset($this->_choices[$value])) {
            $this->_choice    = $this->_choices[$value] ;
            $this->_chosenkey = $value ;
            
        }
    }

    public function setValue($value) {
        
        list ($o1, $o2) = $value ;
        
        $value  = ($o1 == null ? "" : $o1->id) ;
        $value .= "-" ;
        $value .= ($o2 == null ? "" : $o2->id) ;
        
        if (isset($this->_choices[$value])) {
            $this->_choice    = $this->_choices[$value][1] ;
            $this->_chosenkey = $value ;
        }
    }

}
