<?php

namespace Quantyl\Misc\GD ;

class Color extends GDElement {

    private $_image ;
    
    protected function __construct(Image $img, $gdColor) {
        parent::__construct($gdColor) ;
        $this->_image    = $img ;
    }
    
    public function getImage() {
        return $this->_image ;
    }
    
    public function __destruct() {
        imagecolordeallocate($this->_image->getId(), $this->getId());
    }
    
    public function equals(Color $c) {
        return
            $this->_image->equals($c->getImage()) &&
            $this->getId() == $c->getId() ;
    }
    
    public function getRGBA() {
        $rgba = imagecolorsforindex($this->_image->getId(), $this->getId()) ;
        return array(
            $rgba["red"],
            $rgba["green"],
            $rgba["blue"],
            $rgba["alpha"]
        ) ;
    }
    
    
    // Factory
    
    public function FromRGB(Image $img, $red, $green, $blue) {
        $temp = self::Exact($img, $red, $green, $blue) ;
        if ($temp !== null) {
            return $temp ;
        } else {
            return new Color(
                $img,
                imagecolorallocate(
                        $img->getId(),
                        $red,
                        $green,
                        $blue
                        )) ;
        }
    }
    
    public function FromRGBA(Image $img, $red, $green, $blue, $alpha) {
        $temp = self::Exact($img, $red, $green, $blue, $alpha) ;
        if ($temp !== null) {
            return $temp ;
        } else {
            return new Color(
                $img,
                imagecolorallocatealpha(
                        $img->getId(),
                        $red,
                        $green,
                        $blue,
                        $alpha
                        )) ;
        }
    }
    
    public function FromIndex(Image $img, $index) {
        return new Color($img, $index) ;
    }
    
    public function Closest(Image $img, $red, $green, $blue, $alpha = null) {
        if ($alpha == null) {
            $index = imagecolorclosest(
                    $img->getId(),
                    $red,
                    $green,
                    $blue
                    ) ;
        } else {
            $index = imagecolorclosestalpha(
                    $img->getId(),
                    $red,
                    $green,
                    $blue,
                    $alpha
                    ) ;
        }
        return self::FromIndex($img, $index) ;
    }
    
    public function Exact(Image $img, $red, $green, $blue, $alpha = null) {
        if ($alpha == null) {
            $index = imagecolorexact($img->getId(), $red, $green, $blue) ;
        } else {
            $index = imagecolorexactalpha($img->getId(), $red, $green, $blue, $alpha) ;
        }
        if ($index === -1) {
            return null ;
        } else {
            return self::FromIndex($img, $index) ;
        }
    }
    
    
}
