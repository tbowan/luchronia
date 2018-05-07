<?php

namespace Quantyl\XML;

/**
 * Classe pour le texte brut Xml
 *
 * @author henin
 */
class Text extends Element
{

    private $_content;

    /**
     * Construit un contenu textuel au sens XML
     *
     * @param string $content le contenu textuel
     */
    public function __construct($content)
    {
        $this->_content = $content;
    }

    /**
     * Retourne le contenu textuel de cet élément, sans transformation
     *
     * @return string le contenu textuel
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Sérialise en XML
     *
     * Ce contenu ne pouvant pas contenir de balise, elles sont échappées
     * par la fonction htmlentities.
     *
     * @return string le contenu textuel
     */
    public function getXml()
    {
        return htmlentities($this->_content, ENT_COMPAT, "utf-8");
    }

}

?>
