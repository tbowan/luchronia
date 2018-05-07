<?php

namespace Form\Post ;

class Destination extends \Quantyl\Form\Model\Radio {
    
    private $_char ;
    private $_here ;
    private $_cost_parcel ;
    
    public function __construct(
            \Model\Game\Character $recipient,
            \Model\Game\City $here,
            $cost_parcel,
            $label = null, $mandatory = false, $description = null) {
        $this->_char        = $recipient ;
        $this->_here        = $here ;
        $this->_cost_parcel = $cost_parcel ;
        parent::__construct(\Model\Game\City::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = array() ;
        
        $position = $this->_char->position ;
        if ($position->hasTownHall()) {
            $dist = \Model\Game\City::GetDist($this->_here, $position) ;
            $cost = $this->_cost_parcel * $dist ;
            $choices[$position->id] = array(
                \I18n::DESTINATION_POSITION(),
                new \Quantyl\XML\Html\A("/Game/City?id={$position->id}", $position->getName()),
                $dist,
                $cost);
        }
        
        foreach (\Model\Game\Citizenship::GetFromCitizen($this->_char) as $citizenship) {
            $city = $citizenship->city ;
            $dist = \Model\Game\City::GetDist($this->_here, $city) ;
            $cost = $this->_cost_parcel * $dist ;
            $choices[$city->id] = array(
                    \I18n::DESTINATION_CITIZENSHIP(),
                    new \Quantyl\XML\Html\A("/Game/City?id={$city->id}", $city->getName()),
                    $dist,
                    $cost );
        }
        
        return $choices ;
    }
    
    private function getRadio($key, $id, $value) {
        if ($id == $value) {
            $checked = " checked=\"\"" ;
        } else {
            $checked = "" ; ;
        }
        return "\t\t<input"
                    . " type=\"radio\""
                    . " id=\"" . $key . "-" . $id . "\""
                    . " name=\"" . $key . "\""
                    . " value=\"" . $id . "\""
                    . $checked
                    . " />\n" ;
    }
    
    public function getInputHTML($key, $value) {
        
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            "",
            \I18n::DESTINATION_TYPE(),
            \I18n::DESTINATION_NAME(),
            \I18n::DESTINATION_DISTANCE(),
            \I18n::DESTINATION_COST()
        )) ;
        
        foreach ($this->getChoices() as $id => $t) {
            
            $label = "<label for=\"" . $key . "-" . $id . "\">" ;
            $endlabel = "</label>" ;
            
            
            $table->addRow(array(
                $this->getRadio($key, $id, $value),
                $label . $t[0] . $endlabel,
                $t[1],
                $t[2],
                $t[3]
            )) ;
        }
        
        return "" . $table ;
        
    }
    
}
