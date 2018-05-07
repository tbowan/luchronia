<?php

namespace Quantyl\Form\Fields ;

class DateTime extends \Quantyl\Form\Field {
    
    private $_tz ;
    
    public function __construct($label, $tz = null, $mandatory = false) {
        parent::__construct($label, $mandatory);
        $this->_tz = $tz ;
        $this->setValue(time()) ;
    }
    
    protected function getSelect($name, $min, $max, $key, $value) {
        $res  = "<select name=\"{$name}[{$key}]\">\n" ;
        for ($i=$min; $i<=$max; $i++) {
            $res .= "<option value=\"$i\" " ;
            if ($i == $value) {
                $res .= "selected=\"\" " ;
            }
            $res .= ">$i</option>\n" ;
        }
        $res .= "</select>" ;
        return $res ;
    }
    
    protected function getMonthSelect($name, $day) {
        $res  = "<select name=\"{$name}[month]\">\n" ;
        for ($i=1; $i<13; $i++) {
            $res .= "<option value=\"$i\" " ;
            if ($i == $day) {
                $res .= "selected=\"\" " ;
            }
            $res .= ">" . \I18n::translate("MONTH_$i") . "</option>\n" ;
        }
        $res .= "</select>" ;
        return $res ;
    }
    
    public function getInputHTML($name, $values) {
        $res  = $this->getSelect     ($name,    1,   31,    "day", $values["day"])    . " / " ;
        $res .= $this->getMonthSelect($name,                       $values["month"])  . " / " ;
        $res .= $this->getSelect     ($name, 1900, 2016,   "year", $values["year"])   . " | " ;
        $res .= $this->getSelect     ($name,    0,   24,   "hour", $values["hour"])   . " : " ;
        $res .= $this->getSelect     ($name,    0,   59, "minute", $values["minute"]) . " : " ;
        $res .= $this->getSelect     ($name,    0,   59, "second", $values["second"]) . " : " ;
        return $res ;
    }

    public function parse($value) {
        // from array to timestamp
        $values = parent::parse($value);
        
        $year    = intval($values["year"]) ;
        $month   = intval($values["month"]) ;
        $day     = intval($values["day"]) ;
        $hour    = isset($values["hour"])   ? intval($values["hour"])   : 0 ;
        $minute  = isset($values["minute"]) ? intval($values["minute"]) : 0 ;
        $second  = isset($values["second"]) ? intval($values["second"]) : 0 ;
        
        if ($this->_tz === null) {
            return mktime($hour, $minute, $second, $month, $day, $year) ;
        } else {
            return gmmktime($hour - $this->_tz, $minute, $second, $month, $day, $year) ;
        }
    }
    
    public function unparse($value) {
        // from timestamp to array
        $timestamp = intval(parent::unparse($value));
        
        if ($this->_tz === null) {
            return array (
                "day"    => date("j", $timestamp),
                "month"  => date("n", $timestamp),
                "year"   => date("Y", $timestamp),
                "hour"   => date("G", $timestamp),
                "minute" => date("i", $timestamp),
                "second" => date("s", $timestamp)
            ) ;
        } else {
            return array (
                "day"    => gmdate("j", $timestamp),
                "month"  => gmdate("n", $timestamp),
                "year"   => gmdate("Y", $timestamp),
                "hour"   => gmdate("G", $timestamp) + $this->_tz,
                "minute" => gmdate("i", $timestamp),
                "second" => gmdate("s", $timestamp)
                ) ;
        }
    }
    
}

?>
