<?php

namespace Answer\Widget\Avatar ;

class Full extends Base {
    
    protected function getImage(\Model\Game\Avatar\Item $item) {
        $d = dirname($item->filename) ;
        $f = basename($item->filename) ;
        $url = "/Media/icones/Model/Avatar/" . $d . "/" . $f ;
        
        return new \Quantyl\XML\SVG\Image($url, 0, 0, 400, 640) ;
    }

    protected function createEmptySVG() {
        $svg = new \Quantyl\XML\SVG\SVG() ;
        $svg->setViewPort(0, 0, 400, 640) ;
        return $svg ;

    }

}
