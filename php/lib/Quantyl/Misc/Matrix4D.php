<?php

namespace Quantyl\Misc ;

class Matrix4D extends Matrix {

    public function transform3D(Vertex3D $v) {
        $res4D = $this->transform4D($v->toVertex4D()) ;
        return $res4D->toVertex3D() ;
    }
    
    public function transform4D(Vertex4D $v) {
        return Matrix::product($this, $v) ;
    }
    
    
    // FACTORY
    
    /*
     * En radian
     */
    public static function rotationX($alpha) {
        return new Matrix4D(array(
            array(1, 0, 0, 0),
            array(0, cos($alpha), - sin($alpha), 0),
            array(0, sin($alpha),   cos($alpha), 0),
            array(0, 0, 0, 1)
        ), 4, 4) ;
    }
    
    
    public static function rotationY($alpha) {
        return new Matrix4D(array(
            array(cos($alpha),   0, sin($alpha), 0),
            array(0,             1, 0          , 0),
            array(- sin($alpha), 0, cos($alpha), 0),
            array(0, 0, 0, 1)
        ), 4, 4) ;
    }
    
    public static function rotationZ($alpha) {
        return new Matrix4D(array(
            array(cos($alpha), -sin($alpha), 0, 0),
            array(sin($alpha),  cos($alpha), 0, 0),
            array(0, 0, 1, 0),
            array(0, 0, 0, 1)
        ), 4, 4) ;
    }
    
    public static function Rotation3D($alpha, \Misc\Vertex3D $v) {
        return self::RotationXYZ($alpha, $v->x(), $v->y(), $v->z()) ;
    }
    
    public static function RotationXYZ($alpha, $x, $y, $z) {
        
        $v = Vertex3D::XYZ($x, $y, $z) ;
        $v->normalize() ;
        $x = $v->x() ;
        $y = $v->y() ;
        $z = $v->z() ;
        
        echo "Rotation around : $x, $y, $z\n" ;
        
        $c = cos($alpha) ;
        $s = sin($alpha) ;
        
        // http://en.wikipedia.org/wiki/Rotation_matrix
        
        return new Matrix4D(array(
            array(
                $x * $x * (1.0 - $c) +      $c,
                $x * $y * (1.0 - $c) - $z * $s,
                $x * $z * (1.0 - $c) + $y * $s,
                0),
            array(
                $x * $y * (1.0 - $c) + $z * $s,
                $y * $y * (1.0 - $c) +      $c,
                $y * $z * (1.0 - $c) - $x * $s,
                0),
            array(
                $x * $z * (1.0 - $c) - $y * $s,
                $y * $z * (1.0 - $c) + $x * $s,
                $z * $z * (1.0 - $c) +      $c,
                0),
            array(0, 0, 0, 1),
        ), 4, 4) ;
    }
    
    public static function translation3D(Vertex3D $v) {
        return self::translationXYZ($v->x(), $v->y(), $v->z()) ;
    }
    
    public static function translationXYZ($x, $y, $z) {
        return new Matrix4D(
                array(
                    array(1, 0, 0, $x),
                    array(0, 1, 0, $y),
                    array(0, 0, 1, $z),
                    array(0, 0, 0, 1)
                ),
                4, 4
                ) ;
    }
    
    
    // Generic
    
    public static function create($values, $lines = null, $columns = null) {
        
        if ($lines === null || $columns === null) {
            list ($lines, $columns) = self::findDimentions($values) ;
        }
        
        if ($lines == 4 && $columns == 4) {
            return new Matrix4D($values, $lines, $columns) ;
        } else {
            throw new \Exception() ;
        }
    }
}
