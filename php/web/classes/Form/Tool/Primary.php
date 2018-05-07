<?php

namespace Form\Tool ;

class Primary extends Base {
    
    private $_base_coef ;
    private $_primary_coef ;
    
    public function __construct(\Model\Game\Skill\Character $cs, \Model\Game\Tax\Complete $tax, \Model\Game\Building\Instance $instance) {
        $primary_skill = \Model\Game\Skill\Primary::GetFromSkill($cs->skill) ;
        $natural       = \Model\Game\Ressource\Natural::GetFromCityAndItem($instance->city, $primary_skill->in) ;
        $this->_base_coef = round($cs->level * $instance->level * $natural->coef, 2);
        $this->_primary_coef = $primary_skill->coef ;
        
        parent::__construct($cs, $tax);
    }
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->_base_coef * $this->_primary_coef ;
    }

    public function getTaxableAmount($munition) {
        return $this->getAmount($munition) / $this->_primary_coef ;
    }
    
}
