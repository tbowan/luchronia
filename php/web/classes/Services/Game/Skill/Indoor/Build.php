<?php

namespace Services\Game\Skill\Indoor ;

class Build extends Base {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
    }
    
    public function getToolInput() {
        return new \Form\Tool\Build($this->cs, $this->getComplete()) ;
    }

    public function getAmount($munition) {
        return $this->cs->level * parent::getAmount($munition) ;
    }
    
    public function doStuff($points, $data) {
        
        $instance   = $this->inst ;
        $before     = $instance->health ;
        $site       = \Model\Game\Building\Site::GetFromInstance($instance) ;
        $complete   = $site->build($points) ;
        
        $instance->read(true) ;
        $dpoints = $instance->health - $before ;
        
        $msg = "" ;
        if ($complete) {
            // building complete, new building available
            $job = $instance->job ;
            $msg .= \I18n::SKILL_BUILD_COMPLETE($points, $instance->id, $job->getName()) ;
        } else if ($dpoints < $points) {
            // Some points where losts
            $msg .= \I18n::SKILL_BUILD_LOWER($dpoints, $points, $instance->health, $site->getTargetHealth()) ;
        } else {
            $msg .= \I18n::SKILL_BUILD_NORMAL($points, $instance->health, $site->getTargetHealth()) ;
        }
        $msg .= parent::doStuff($points, $data) ;
        return $msg ;
    }

    public function getMessage() {
        
        $res = parent::getMessage() ;
        
        $site       = \Model\Game\Building\Site::GetFromInstance($this->inst) ;
        $hp         = $site->instance->health ;
        $percent    = $site->getNeedCompletion() ;
        $target     = $site->getTargetHealth() ;
        
        $table = new \Quantyl\XML\Html\Table() ;
        
        $table->addRow(array(\I18n::CURRENT_BUILD_POINTS(), number_format($hp, 2) )) ;
        $table->addRow(array(\I18n::MAXIMUM_BUILD_POINTS(), number_format($target * $percent, 2) )) ;
        $table->addRow(array(\I18n::TARGET_BUILD_POINTS(), $target )) ;
        
        $res .= \I18n::SKILL_BUILD_INFO() . $table;
        
        return $res ;
    }

}
