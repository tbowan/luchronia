<?php

namespace Quantyl\Answer ;

use \Quantyl\Answer\Answer ;

abstract class Template extends Answer {

    private $_tplfile ;
    
    public function __construct($tplfile) {
        parent::__construct() ;
        $this->_tplfile = $tplfile ;
    }
    
    public function sendHeaders($srv) {
        // $srv->header("Content-type: text/html");
        $srv->header("Content-type: application/xhtml+xml");
    }
    
    public function getContent() {
        global $global_view ;
        $global_view = $this ;
        ob_start() ;
        include $this->_tplfile ;
        return ob_get_clean() ;
    }

}
