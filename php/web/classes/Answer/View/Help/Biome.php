<?php

namespace Answer\View\Help;

class Biome extends \Answer\View\Base {

    private $_biome;

    public function __construct($service, \Model\Game\Biome $b) {
        parent::__construct($service);
        $this->_biome = $b;
    }

    public function getTplContent() {
        return ""
                . new \Answer\Widget\Help\Biome\Description($this->_biome, "card-1-2")
                . new \Answer\Widget\Help\Biome\Ressources($this->_biome, "card-1-2")
                ; 
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP(ucfirst($this->_biome->getName()));
    }

}
