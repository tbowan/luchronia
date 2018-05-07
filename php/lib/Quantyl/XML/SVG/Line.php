<?php

namespace Quantyl\XML\SVG ;
/**
 * Lignes (segment de droite)
 *
 * @author henin
 */
class Line extends Shape
{

    /**
     * Construit une ligne en fonction de ses deux extremités
     *
     * @param int $x1 abscisse du point de départ
     * @param int $y1 ordonnée du point de départ
     * @param int $x2 abscisse du ponit d'arrivée
     * @param int $y2 ordonnée du ponit d'arrivée
     */
    public function __construct($x1, $y1, $x2, $y2)
    {
        parent::__construct("line");
        $this->setAttribute("x1", $x1);
        $this->setAttribute("y1", $y1);
        $this->setAttribute("x2", $x2);
        $this->setAttribute("y2", $y2);
    }
}

?>
