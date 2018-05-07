<?php

namespace Quantyl\Misc ;

class Vertex extends Matrix {
    
    protected function __construct($values, $lines) {
        parent::__construct($values, $lines, 1) ;
    }
    
    public function getValue($i) {
        return parent::getValue($i, 0) ;
    }
    
    /**
     * Calcule la longueur du vecteur, i.e. sa distance à l'origine
     *
     * @return type
     */
    public function length()
    {
        return sqrt($this->ScalarProduct($this));
    }
    
    /**
     * Normalise le vecteur, i.e. l'étire/rétracte pour avoir une longueur de 1
     */
    public function normalize()
    {
        $this->multiply(1.0 / $this->length()) ;
    }
    
    /**
     * Calcule le produit scalaire avec le vecteur en paramètre
     *
     * @param \Tools\Vertex3D $v le vecteur avec lequel calculer le produit
     *
     * @return float le produit scalaire
     */
    public function ScalarProduct(Vertex $v)
    {
        if ($v->getLines() != $this->getLines()) {
            throw new \Exception() ;
        }
        
        $sum = 0 ;
        for ($i=0; $i < $this->getLines(); $i++) {
            $v1 = $this->getValue($i) ;
            $v2 = $v->getValue($i) ;
            $sum += $v1 * $v2 ;
        }
        return $sum ;
    }
    
    public static function create($values, $lines = null, $columns = null) {
        if ($lines === null || $columns === null) {
            list ($lines, $columns) = self::findDimentions($values) ;
        }
        
        if ($columns != 1) {
            throw new \Exception() ;
        } else if ($lines == 3) {
            return Vertex3D::create($values, $lines, $columns) ;
        } else if ($lines == 4) {
            return Vertex4D::create($values, $lines, $columns) ;
        } else {
            return new Vertex($values, $lines) ;
        }
    }
    
}
