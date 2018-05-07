<?php

namespace Quantyl\Misc\GD ;

/**
 *  Wrapper autour de la librairie GD
 * 
 */

class Image extends GDElement {
    
    public function __destruct() {
        imagedestroy($this->getId());
    }
    
    public function equals(Image $i) {
        return $this->getId() == $i->getId() ;
    }
    
    // Get Information
    
    public function getWidth() {
        return imagesx($this->getId()) ;
    }
    
    public function getHeight() {
        return imagesy($this->getId()) ;
    }
    
    public function getSize() {
        return array ($this->getWidth(), $this->getHeight()) ;
    }
    
    public function getColor($x, $y) {
        return Color::FromIndex($this, imagecolorat($this->getId(), $x, $y)) ;
    }
    
    public function getColorCount() {
        return imagecolorstotal($this->getId()) ;
    }
    
    public function isTrueColor() {
        return imageistruecolor($this->getId()) ;
    }
    
    // Modify image
    
    public function setBlending($bool) {
        return imagealphablending($this->getId(), $bool) ;
    }
    
    public function setAntiAliasing($bool) {
        return imageantialias($this->getId(), $bool) ;
    }
    
    // Drawing
    
    public function setTransparent() {
        imagesavealpha($this->getId(), true) ;
        $c = Color::FromRGBA($this, 0, 0, 0, 127) ;
        $this->fill($c) ;
    }
    
    public function fill(Color $c) {
        return imagefill($this->getId(), 0, 0, $c->getId()) ;
    }
    
    public function setPixel($x, $y, Color $c) {
        return imagesetpixel($this->getId(), $x, $y, $c->getId()) ;
    }
    
    /**
     * 
     * @param type $cx Centre du cercle
     * @param type $cy Centre du cercle
     * @param type $width largeur
     * @param type $height hauteur
     * @param type $start début (en degré)
     * @param type $end fin (en degré)
     * @param \Misc\GD\Color $color la couleur
     * @return type
     */
    public function drawArc($cx, $cy, $width, $height, $start, $end, Color $color) {
        return imagearc(
                $this->getId(),
                $cx, $cy,
                $width, $height,
                $start, $end,
                $color->getId()) ;
    }
    
    
    // Writing
    
    public function writeH($x, $y, $c, Color $color) {
        imagechar($this->getId(), 1, $x, $y, $c, $color->getId()) ;
    }
    
    public function writeV($x, $y, $c, Color $color) {
        imagecharup($this->getId(), 1, $x, $y, $c, $color->getId()) ;
    }


    // operation on image
    
    public function affine() {
        // TODO :
        // - imageaffine
        // - imageaffinematrixconcat
        // - imageaffinematrixget
        return ;
    }
    
    public function convolution($matrix, $div, $offset) {
        imageconvolution($this->getId(), $matrix, $div, $offset) ;
    }
    
    // opérations entre images
    
    public function copy(Image $image, $offx, $offy, $alpha = 100) {
        return imagecopymerge(
                $this->getId(),
                $img->getId(),
                $offx, $offy, // coordonnée d'arrivée
                0, 0,     // coordonnée de début
                $image->getWidth(), $image->getHeigh(),      // taille de la zone
                $alpha
                ) ;
    }
    
    public function copyClipped(Image $img, $offx, $offy, $dx, $dy, $dw, $dh, $alpha = 100 ) {
        return imagecopy(
                $this->getId(),
                $img->getId(),
                $offx, $offy, // coordonnée d'arrivée
                $dx, $dy,     // coordonnée de début
                $dw, $dh,      // taille de la zone
                $alpha
                ) ;
    }
    
    /* fonction non définie !?
    public function crop($x, $y, $w, $h) {
        $id = imagecrop($this->getId(), array (
            "x"      => $x,
            "y"      => $y,
            "width"  => $w,
            "height" => $h
        )) ;
        return new Image($id) ;
    }
     */
    
    // Render
    
    public function asWBMP() {
        image2wbmp($this->getId()) ;
    }
    
    public function asPNG() {
        imagepng($this->getId()) ;
    }
    
    public function writePNG($filename) {
        ob_start() ;
        $this->asPNG() ;
        $content = ob_get_clean() ;
        file_put_contents($filename, $content) ;
    }
    
    
    // Factory
    
    public static function FromFile($filename) {
        $info = getimagesize($filename) ;
        $type = $info["mime"] ;
        
        switch ($type) {
            case "image/png"  : return self::FromPNG($filename) ;
            case "image/gif"  : return self::FromGIF($filename) ;
            case "image/jpeg" : return self::FromJPEG($filename) ;
            case "image/png"  : return self::FromPNG($filename) ;
            default           : throw new \Exception() ; // TODO : better exception
        }
        
    }
    
    public static function FromJPEG($filename) {
        return new Image(imagecreatefromjpeg($filename)) ;
    }
    
    public static function FromPNG($filename) {
        return new Image(imagecreatefrompng($filename)) ;
    }
    
    public static function FromGIF($filename) {
        return new Image(imagecreatefromgif($filename)) ;
    }
    
    public static function CreateEmpty($width, $height) {
        return new Image(imagecreatetruecolor($width, $height)) ;
    }
    
}
