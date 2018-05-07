<?php

namespace Quantyl\XML\SVG ;
/**
 * Description of Shape
 *
 * @author henin
 */
class Shape extends \Quantyl\XML\Base
{
    
    /**
     * Crée une forme SVG d'arpès son nom
     *
     * @param string $name le nom de la forme
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * Spécifie le style à appliquer à cet élément
     * @param string $style le style
     */
    public function setStyle($style)
    {
        $this->setAttribute("style", $style);
    }
    
    /**
     * Ajoute un style supplémentaire
     */
    public function addStyle($style)
    {
        $this->setStyle(
            $this->getAttribute("style") . ";" . $style
            );
    }

}

?>
