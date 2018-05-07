<?php

namespace Quantyl\Answer ;

class File extends Answer {
    
    private $_filename ;
    
    public function __construct($filename) {
        $this->_filename = $filename ;
        if (! is_file($filename)) {
            throw new \Quantyl\Exception\Http\ClientNotFound() ;
        }
    }
    
    public function getContent() {
        return file_get_contents($this->_filename) ;
    }

    public function sendHeaders(\Quantyl\Server\Server $srv) {
        // Detect mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE) ;
        $mime = finfo_file($finfo, $this->_filename) ;
        
                
        $srv->header("Content-Type: "   . $mime ) ;
        $srv->header("Content-Length: " . filesize($this->_filename)) ;
    }

}
