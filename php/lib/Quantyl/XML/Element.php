<?php

namespace Quantyl\XML;

/**
 * interface commune à tous les éléments XML
 *
 * @author henin
 */
abstract class Element extends \Quantyl\Answer\Widget
{

    /**
     * fonction commune : sérialisation XML
     *
     * @return string le code XML
     */
    public abstract function getXml();
    
    public final function getContent() {
        return $this->getXml() ;
    }
    
    public function __toString() {
        return $this->getXml() ;
    }
    
}

?>
