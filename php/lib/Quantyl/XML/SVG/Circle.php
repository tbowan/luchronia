<?php


namespace Quantyl\XML\SVG ;

/**
 * Cercle SVG
 */
class Circle extends Shape
{
    /**
     * Construit un cercle SVG
     * 
     * @param int $cx     l'abscisse du centre
     * @param int $cy     l'ordonnée du centre
     * @param int $radius le rayon
     */
    public function __construct($cx, $cy, $radius)
    {
        parent::__construct("circle");
        $this->setAttribute("cx", $cx);
        $this->setAttribute("cy", $cy);
        $this->setAttribute("r", $radius);
    }
    
}

?>
