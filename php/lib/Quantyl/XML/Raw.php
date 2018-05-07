<?php

namespace Quantyl\XML;

/**
 * Classe pour le contenu qui est déjà une chaine de caractère XML
 *
 * @author henin
 */
class Raw extends Element
{

    private $_content;

    /**
     * Construit un contenu déjà formaté en XML
     *
     * @param string $content le code XML
     */
    public function __construct($content)
    {
        $this->_content = $content;
    }

    /**
     * Sérialisation XML
     *
     * Dans ce cas particulier, retourne simplement le contenu de l'attribut
     * "_content" qui contient le code déjà formaté en XML
     *
     * @return string le code XML
     */
    public function getXml()
    {
        return $this->_content;
    }

}

?>
