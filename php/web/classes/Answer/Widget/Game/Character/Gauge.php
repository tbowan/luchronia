<?php

namespace Answer\Widget\Game\Character ;

class Gauge extends \Answer\Widget\Misc\Section  {
    
    public function __construct(\Model\Game\Character $c, $classes) {
    
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::GAUGE_ELEMENT(),
            \I18n::VALUE(),
            \I18n::MAXIMUM(),
            \I18n::GAUGE(),
            \I18n::REMAIN()
                )) ;
        
        $this->_addEnergy($c, $table) ;
        $this->_addHydration($c, $table) ;
        $this->_addHealth($c, $table) ;
        $this->_addTime($c, $table) ;
        $this->_addExperience($c, $table) ;
        
        parent::__construct(\I18n::GAUGE(), "", "", "$table", $classes) ;
        
    }
    
    private function _addTime($c, &$table) {
        
        $v = $c->getTime() ;
        $m = $c->getMaxTime() ;
        
        $table->addRow(array(
            \I18n::TIME_ICO() . " " . \I18n::TIME(), $v, $m,
            new \Quantyl\XML\Html\Meter(0, $m, $v),
            \I18n::_time_delay($m - $v)
        )) ;
    }

    private function _addEnergy($c, &$table) {
        
        $v = $c->getEnergy() ;
        $m = $c->getMaxEnergy() ;
        
        $table->addRow(array(
            \I18n::ENERGY_ICO() . " " . \I18n::ENERGY(), $v, $m,
            new \Quantyl\XML\Html\Meter(0, $m, $v, 1, 749, 750),
            \I18n::_time_delay(60 * $v / 0.5)
        )) ;
    }
    
    private function _addHydration($c, &$table) {
        
        $v = $c->getHydration() ;
        $m = $c->getMaxHydration() ;
        
        $table->addRow(array(
            \I18n::HYDRATION_ICO() . " " . \I18n::HYDRATION(), $v, $m,
            new \Quantyl\XML\Html\Meter(0, $m, $v, 1, 749, 750),
            \I18n::_time_delay(60 * $v / 0.5)
        )) ;
    }
    
    private function _addHealth($c, &$table) {
        
        $v = $c->getHealth() ;
        $m = $c->getMaxHealth() ;
        
        $table->addRow(array(
            \I18n::HEALTH_ICO() . " " . \I18n::HEALTH(), $v, $m,
            new \Quantyl\XML\Html\Meter(0, $m, $v, 750),
            "-"
        )) ;
    }
    
    private function _addExperience($c, &$table) {
        
        $v = $c->getExperience() ;
        $m = $c->getExpThreshold() ;
        
        $table->addRow(array(
            \I18n::XP_ICO() . " " . \I18n::EXPERIENCE(), $v, $m,
            new \Quantyl\XML\Html\Meter(0, $m, $v),
            \I18n::_time_delay($m - $v)
        )) ;
    }
    
}
