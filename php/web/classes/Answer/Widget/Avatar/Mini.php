<?php

namespace Answer\Widget\Avatar ;

class Mini extends Base {
    
    protected function getImage(\Model\Game\Avatar\Item $item) {
        $d = dirname($item->filename) ;
        $f = basename($item->filename) ;
        $url = "/Media/icones/Model/Avatar/" . $d . "/mini-" . $f ;
        
        return new \Quantyl\XML\SVG\Image($url, 0, 0, 50, 80) ;
    }

    protected function createEmptySVG() {
        $svg = new \Quantyl\XML\SVG\SVG() ;
        $svg->setViewPort(0, 0, 50, 80) ;
        return $svg ;
    }

}
