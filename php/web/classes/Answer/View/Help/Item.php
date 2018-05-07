<?php

namespace Answer\View\Help ;

class Item extends \Answer\View\Base {
    
    private $_item ;
    private $_viewer ;
    
    public function __construct($service, \Model\Game\Ressource\Item $i, $viewer) {
        parent::__construct($service) ;
        $this->_item   = $i ;
        $this->_viewer = $viewer ;
    }
    
    public function getNeedBy($class = "") {
        $skills = array() ;
        foreach (\Model\Game\Skill\Primary::GetFromIn($this->_item) as $p) {
            $skills[] = $p->skill ;
        }
        foreach (\Model\Game\Skill\In::GetFromItem($this->_item) as $in) {
            $skills[] = $in->skill ;
        }
        
        if (count($skills) > 0) {
            return new \Answer\Widget\Help\Item\NeedBy($skills, $class) ;
        } else {
            return "" ;
        }
    }
    
    public function getProducedBy($class = "") {
        $skills = array() ;
        foreach (\Model\Game\Skill\Primary::GetFromOut($this->_item) as $p) {
            $skills[] = $p->skill ;
        }
        foreach (\Model\Game\Skill\Out::GetFromItem($this->_item) as $out) {
            $skills[] = $out->skill ;
        }
        $map = \Model\Game\Building\Map::getFromItem($this->_item) ;
        if ($map != null) {
            $skills[] = $map->skill ;
        }
        
        if (count($skills) > 0) {
            return new \Answer\Widget\Help\Item\ProducedBy($skills, $class) ;
        } else {
            return "" ;
        }
    }

    
    public function getTplContent() {
        return ""
                . new \Answer\Widget\Help\Item\Description($this->_item)
                . new \Answer\Widget\Help\Item\CraftingIn($this->_item, $this->_viewer)
                . new \Answer\Widget\Help\Item\CraftingOut($this->_item, $this->_viewer)
                ;
    }
    
    public function getTitle() {
        return \I18n::TITLE_HELP($this->_item->getName()) ;
    }
    
}
