<?php

namespace Quantyl\XML;

/**
 * Description of XmlDocument
 *
 * @author henin
 */
class Document extends \Quantyl\Answer\Widget
{

    /**
     * La racine XML du document
     *
     * @var Element
     */
    private $_root;

    private $_doctype ;
    
    /**
     * Construit un document en spécifiant la racine
     *
     * @param \XML\Element $root la racine du document
     */
    public function __construct(Element $root, $doctype = null)
    {
        $this->_root = $root;
        $this->_doctype = $doctype ;
    }

    /**
     * Retourne la racine
     *
     * @return Element la racine
     */
    public function getRoot()
    {
        return $this->_root;
    }

    /**
     * Fonction de commodité, ajoute un fils à la racine
     *
     * @param Element $child le fils à ajouter
     *
     * @return Element retourne le fils ajouté
     */
    public function addChild(Element $child)
    {
        return $this->_root->addChild($child);
    }

    /**
     * Ecrit le contenu du document xml
     *
     * Dans le fichier spécifié s'il l'est, sur la sortie standard par défaut
     *
     * @param string $filename le nom du fichier
     *
     * @return void ne retourne rien
     */
    public function writeStandalone($filename = null)
    {
        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        if ($this->_doctype != null) {
            $content .= $this->_doctype ;
        }
        $content .= $this->_root->getXml();

        if ($filename === null) {
            return $content;
        } else {
            file_put_contents($filename, $content);
        }
    }

    public function getContent() {
        return $this->writeStandalone() ;
    }

    public function isDecorable() {
        return false ;
    }
}

?>
