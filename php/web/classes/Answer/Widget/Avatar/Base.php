<?php

namespace Answer\Widget\Avatar ;

abstract class Base extends \Quantyl\Answer\Answer {
 
    private $_char ;
    private $_doc ;
    
    public function __construct(\Model\Game\Character $c) {
        $this->_char = $c ;
        
        
        $svg = $this->createEmptySVG() ;
        foreach ($this->getItems() as $item) {
            $svg->addChild($this->getImage($item)) ;
        }
        
        $this->_doc = new \Quantyl\XML\SVG\Document($svg,
                "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">"
                ) ;
        
    }

    private function getItems() {
        $used = \Model\Game\Avatar\Used::GetFromCharacter($this->_char) ;
        $items = array() ;
        foreach ($used as $u) {
            $i = $u->item ;
            $items[$i->layer->getId()] = $i ;
        }
        ksort($items) ;
        return $items ;
    }
    
    protected abstract function createEmptySVG() ;
    
    protected abstract function getImage(\Model\Game\Avatar\Item $item) ;
    
    public function getContent() {
        return $this->_doc->getContent() ;
    }
    
    public function sendHeaders(\Quantyl\Server\Server $srv) {
        return $this->_doc->sendHeaders($srv) ;
    }
    
    public function isDecorable() {
        return false ;
    }
    
}