<?php

namespace Quantyl\XML\SVG;

/**
 * Description of SVG
 *
 * @author henin
 */
class Document extends \Quantyl\XML\Document {
    
    public function __construct($root) {
        parent::__construct($root, "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">") ;
    }
    
    public function sendHeaders(\Quantyl\Server\Server $srv) {
        $srv->header("Content-type: image/svg+xml") ;
    }

    
}
