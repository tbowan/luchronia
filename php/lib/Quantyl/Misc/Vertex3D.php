<?php

namespace Quantyl\Misc ;

class Vertex3D extends Vertex
{

    public function x() {
        return $this->getValue(0) ;
    }
    
    public function y() {
        return $this->getValue(1) ;
    }
    
    public function z() {
        return $this->getValue(2) ;
    }

    /**
     * Calcule le produit vectoriel avec le vecteur passé en paramètre.
     *
     * @param \Tools\Vertex3D $v le vecteur avec lequel cacluler le produit
     *
     * @return \Tools\Vertex3D le produit vectoriel
     */
    public static function VectorProduct(Vertex3D $v1, Vertex3D $v2)
    {
        return Vertex3D::XYZ(
            $v1->y() * $v2->z() - $v1->z() * $v2->y(),
            $v1->z() * $v2->x() - $v1->x() * $v2->z(),
            $v1->x() * $v2->y() - $v1->y() * $v2->x()
            ) ;
    }

    

    public function lattitude($deg = false)
    {
        $res = asin($this->y() / $this->length()) ;
        if ($deg) {
            $res = rad2deg($res) ;
        }
        return $res ;
    }

    public function longitude($deg = false)
    {
        if ($this->x() == 0.0 && $this->z() == 0) {
            $res = 0.0 ;
        } else {
            $res = acos($this->z() / sqrt($this->z() * $this->z() + $this->x() * $this->x())) ;
        }
        if ($this->x() < 0) {
            $res = - $res ;
        }
        if ($deg) {
            $res = rad2deg($res) ;
        }
        return $res ;
    }
    
    // FACTORY
    
    public static function FromVertex4D(Vertex4D $v) {
        $res = self::XYZ($v->x(), $v->y(), $v->z()) ;
        $res->multiply(1.0 / $v->w()) ;
        return $res ;
    }
    
    public function toVertex4D() {
        return Vertex4D::FromVertex3D($this) ;
    }
    
    public static function create($values, $lines = null, $columns = null) {
        if ($lines === null || $columns === null) {
            list ($lines, $columns) = self::findDimentions($values) ;
        }
        if ($columns != 1 && $lines != 3) {
            throw new \Exception() ;
        } else {
            return new Vertex3D($values, $lines, $columns) ;
        }
    }
    
    public static function XYZ($x, $y, $z) {
        return self::create(array(
            array($x),
            array($y),
            array($z),
            ),3, 1);
    }
    
    /**
     * From :  http://fr.wikipedia.org/wiki/Coordonn%C3%A9es_sph%C3%A9riques
     * le repère est orienté en OpenGL (main droite)
     */
    public static function FromSpheric($long, $lat, $alt = 1.0, $deg = false) {
        
        if ($deg) {
            return self::FromSpheric(deg2rad($long), deg2rad($lat), $alt, false) ;
        } else {
        
            return self::XYZ(
                    $alt * sin($long) * cos($lat),
                    $alt *              sin($lat),
                    $alt * cos($long) * cos($lat)
                    ) ;
            
            
        }
    }
    
    public function __toString() {
        return ""
                . "("
                . $this->x()
                . ", "
                . $this->y()
                . ", "
                . $this->z()
                . ")"
                . "" ;
    }
    
}

?>
