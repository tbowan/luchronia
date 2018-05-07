<?php

namespace Quantyl\XML\SVG;

/**
 * Un rectangle en SVG
 *
 * @author henin
 */
class Rect extends Shape
{

    /**
     * Construit un rectangle de base
     *
     * @param int $width  la largeur du rectangle
     * @param int $height la hauteur du rectangle
     * @param int $rx la courbure en x
     * @param int $ry la courbure en y
     */
    public function __construct($width, $height)
    {
        parent::__construct("rect");
        switch(func_num_args()){
            case 2:
                $this->setDimension(func_get_arg(0), func_get_arg(1));
                break;
            case 4:
                $this->setDimension(func_get_arg(0), func_get_arg(1));
                $this->setAttribute("rx", func_get_arg(2));
                $this->setAttribute("ry", func_get_arg(3));
                break;               
        }

    }
    
    /**
     * Spécifie la position du coin de base
     *
     * @param int $x abscisse
     * @param int $y ordonnée
     *
     * @return void ne retourne rien
     */
    public function setPos($x, $y)
    {
        $this->setAttribute("x", $x);
        $this->setAttribute("y", $y);

        return $this;
    }

    /**
     * Spécifie les dimensions du rectangle
     *
     * @param int $width  largeur
     * @param int $height hauteur
     *
     * @return void ne retourne rien
     */
    public function setDimension($width, $height)
    {
        $this->setAttribute("width", $width);
        $this->setAttribute("height", $height);
    }

    /**
     * Spécifie la position du coin opposé
     *
     * @param int $x abscisse
     * @param int $y ordonnée
     *
     * @return void ne retourne rien
     */
    public function setEnd($x, $y)
    {
        $this->setDimension(
            $x - $this->getAttribute("x"),
            $y - $this->getAttribute("y")
        );
    }

}

?>
