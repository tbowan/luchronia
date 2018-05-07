<?php

namespace Quantyl\Misc ;

class Vertex4D extends Vertex {
    
    public function x() {
        return $this->getValue(0) ;
    }
    
    public function y() {
        return $this->getValue(1) ;
    }
    
    public function z() {
        return $this->getValue(2) ;
    }
    
    public function w() {
        return $this->getValue(3) ;
    }
    
    public static function FromVertex3D(Vertex3D $v) {
        return self::XYZW($v->x(), $v->y(), $v->z(), 1) ;
    }
    
    public function toVertex3D() {
        return Vertex3D::FromVertex4D($this) ;
    }
    
    public static function create($values, $lines = null, $columns = null) {
        if ($lines === null || $columns === null) {
            list ($lines, $columns) = self::findDimentions($values) ;
        }
        if ($columns != 1 && $lines != 4) {
            throw new \Exception() ;
        } else {
            return new Vertex4D($values, $lines, $columns) ;
        }
    }
    public static function XYZW($x, $y, $z, $w) {
        return self::create(array(
            array($x),
            array($y),
            array($z),
            array($w))
            ,4, 1);
    }
}
