<?php

namespace Quantyl\XML;

/**
 * Classe SvgElement
 *
 * Cette classe est la mère de tous les éléments SVG et défini les parties
 * communes (nom, attributs, écriture XML, ...).
 *
 * @author henin
 */
abstract class Base extends Element
{

    /**
     * Nom de la balise XML
     * @var string le nom de la balise
     */
    private $_tag;

    /**
     * La liste des attributs de la balise (clés/valeurs)
     * @var array les clés/valeurs des attributs
     */
    private $_attrs;

    /**
     * Les fils de cet élément.
     * @var type
     */
    private $_children;

    /**
     * Construit un élément vide
     *
     * @param string $tag le nom de la balise
     */
    public function __construct($tag = null)
    {
        $this->_tag = $tag;
        $this->_attrs = array();
        $this->_children = array();
    }

    /**
     * Récupère le nom de la balise
     *
     * @return string le nom de la balise
     */
    public function getTag()
    {
        return $this->_tag;
    }

    /**
     * Récupère la valeur d'un attribut spécifique
     *
     * @param string $name le nom de l'attribut
     *
     * @return mixed sa valeur
     */
    public function getAttribute($name = null)
    {
        if ($name === null) {
            return $this->_attrs;
        } else {
            return $this->_attrs[$name];
        }
    }

    /**
     * Ajoute/affecte la valeur d'un attribut
     *
     * @param string $name  la clé / nom de l'attribut
     * @param mixed  $value la valeur
     *
     * @return void ne retourne rien
     */
    public function setAttribute($name, $value)
    {
        $this->_attrs[$name] = $value;
    }

    /**
     * Ajoute un fils
     *
     * @param \SVG\XMLElement $child le fils à ajouter
     *
     * @return \SVG\XMLElement le fils qui vient d'être ajouté
     */
    public function addChild(Element $child)
    {
        $this->_children[] = $child;
        return $child;
    }

    /**
     * retourne les fils de l'élément, peut être filtré par nom de balise
     *
     * @param string $tag si spécifié, filtre par nom de balise
     *
     * @return \SVG\XMLElement tableau contenant les fils
     */
    public function getChildren($tag = null)
    {
        if ($tag === null) {
            return $this->_children;
        } else {
            $res = array();
            foreach ($this->_children as $child) {
                if ($child->geTag() == $tag) {
                    $res[] = $child;
                }
            }
            return $res;
        }
    }

    /**
     * Serialize l'élément en XML
     *
     * @return string code XML de l'élément
     */
    public function getXml()
    {
        $res = "<{$this->_tag} ";
        foreach ($this->_attrs as $k => $v) {
            $res .= "$k=\"" . htmlspecialchars($v) . "\" ";
        }
        if (count($this->_children) == 0) {
            $res .= "/>\n";
        } else {
            $res .= ">\n";
            foreach ($this->_children as $child) {
                $res .= $child->getXml();
            }
            $res .= "</{$this->_tag}>\n";
        }

        return $res;
    }
    
    public function __toString() {
        return $this->getXml();
    }

}

?>
