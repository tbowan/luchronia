<?php

namespace Scripts\Ephemeris ;

class DrawDayNight extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("time",   new \Quantyl\Form\Fields\Text()) ;
        $form->addInput("width",  new \Quantyl\Form\Fields\Integer()) ;
        $form->addInput("height", new \Quantyl\Form\Fields\Integer()) ;
        $form->addInput("file",   new \Quantyl\Form\Fields\Text()) ;
        
    }
    
    private function FromCoord($i, $j) {
        $long =        360.0 * $i / $this->width  - 180.0 ;
        $latt = 90.0 - 180.0 * $j / $this->height ;
        return \Quantyl\Misc\Vertex3D::FromSpheric($long, $latt, 1.0, true) ;
    }
    
    private function fillImage($image, $sun, $cnt) {
        for ($i=0; $i<$this->width; $i += 1) {
            for ($j=0; $j<$this->height; $j += 1) {
                
                $p = $this->FromCoord($i, $j) ;
                $cf = $sun->ScalarProduct($p) ;
                
                $alpha = ($cf >= 0 ? 127 : 64) ;
                $color = \Quantyl\Misc\GD\Color::FromRGBA($image, 0, 0, 0, $alpha) ;
                
                $image->setPixel($i, $j, $color) ;
                $cnt->step() ;
            }
        }
    }
    
    public function getView() {
        
        $sun    = \Model\Game\Ephemeris\Sun::GetPosByTime(strtotime($this->time)) ;
        $image  = \Quantyl\Misc\GD\Image::CreateEmpty($this->width, $this->height) ;
        $image->setTransparent() ;
        $cnt    = new \Quantyl\Misc\Counter($this->width * $this->height) ;
        
        $this->fillImage($image, $sun, $cnt) ;
        
        $cnt->elapsed() ;
        $image->writePNG($this->file) ;
    }
    
}
