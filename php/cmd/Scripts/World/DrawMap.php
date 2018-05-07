<?php

namespace Scripts\World ;

use Model\Game\City;
use Model\Game\World;
use Quantyl\Answer\NullAnswer;
use Quantyl\Form\Fields\Boolean;
use Quantyl\Form\Fields\Integer;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Misc\Counter;
use Quantyl\Misc\GD\Color;
use Quantyl\Misc\GD\Image;
use Quantyl\Misc\Vertex3D;
use Quantyl\Request\Request;
use Quantyl\Service\EnhancedService;

class DrawMap extends EnhancedService {
    
    private $_last ;
    
    public function fillParamForm(Form &$form) {
        $form->addInput("world",    new Id(World::getBddTable())) ;
        $form->addInput("filename", new Text()) ;
        $form->addInput("width",    new Integer())->setValue(360) ;
        $form->addInput("height",   new Integer())->setValue(180) ;
        $form->addInput("v",        new Integer())->setValue(3) ;
    }
    
    public function init() {
        parent::init() ;

        if ($this->width == 0) {
            $this->width = 360 ;
        }

        if ($this->height == 0) {
            $this->height = 180 ;
        }

        if ($this->v == null) {
            $this->v = 3 ;
        }

    }
    
    function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return $rgb;
    }
    
    public function getColorFromCoord($x, $y, $image) {
        $long =        360.0 * $x / $this->width  - 180.0 ;
        $latt = 90.0 - 180.0 * $y / $this->height ;
        
        $p    = Vertex3D::FromSpheric($long, $latt, 1.0, true) ;
        $city =     City::GetClosest($this->world, $p, $this->v, $this->_last) ;
        $this->_last = $city ;
        
        return $this->getColorFromCity($city, $image) ;
    }
    
    public function getColorAlbedo($city, $image) {
        $r = $g = $b = 256 - $city->albedo ;
        return Color::FromRGB($image,$r, $g, $b) ;
    }
    
    public function getColorBiome($city, $image) {
        $biome = $city->biome ;
        if ($city->sun > 0) {
            list ($r, $g, $b) = $this->hex2rgb($biome->day_color) ;
        } else {
            list ($r, $g, $b) = $this->hex2rgb($biome->night_color) ;
        }
        return Color::FromRGB($image,$r, $g, $b) ;
    }
    
    private static $item ;
    
    public function getColorItem($city, $image) {
        if (self::$item === null) {
            self::$item = \Model\Game\Ressource\Item::GetByName("Gresbo") ;
        }
        $nat = \Model\Game\Ressource\Natural::GetFromCityAndItem($city, self::$item) ;
        if ($nat === null) {
            $g = 0 ;
        } else {
            $g = 256 * $nat->coef ;
        }
        return Color::FromRGB($image,$g, $g, $g) ;
    }
    
    public function getColorFromCity($city, $image) {
        return $this->getColorBiome($city, $image) ;
        // return $this->getColorItem($city, $image) ;
    }
    
    public function getView() {
        
        $image = Image::CreateEmpty($this->width, $this->height) ;
        
        $cnt = new Counter($this->width * $this->height) ;
        
        
        for ($i=0; $i<$this->width; $i += 1) {
            $this->_last = null ;
            for ($j=0; $j<$this->height; $j += 1) {
                $image->setPixel($i, $j, $this->getColorFromCoord($i, $j, $image)) ;
                $cnt->step() ;
            }
            $image->writePNG($this->filename) ;
        }
        
        $cnt->elapsed() ;
        
        echo "- done\n" ;
        
        $image->writePNG($this->filename) ;
    }
    
}
