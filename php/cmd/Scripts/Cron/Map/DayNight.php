<?php

namespace Scripts\Cron\Map ;

class DayNight extends Base {
    
    protected $_sun ;
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        parent::fillParamForm($form);
        $form->addInput("date", new \Quantyl\Form\Fields\Integer()) ;
    }
    
    public function init() {
        $world = $this->world ;
        $when = ($this->date == 0 ? time() : $this->date) ;
        $this->_sun = \Model\Game\Ephemeris\Sun::GetPosByTime($when) ;
    }
    
    public function getColorFromCity($image, $city) {
        $p      = \Quantyl\Misc\Vertex3D::XYZ($city->x, $city->y, $city->z) ;
        $cf     = $this->_sun->ScalarProduct($p) ;
        $alpha  = ($cf >= 0 ? 127 : 64) ;
        $color  = \Quantyl\Misc\GD\Color::FromRGBA($image, 0, 0, 0, $alpha) ;
        
        return $color ;

    }

}
