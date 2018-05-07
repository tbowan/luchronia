<?php

namespace Widget ;
class Exception extends \Quantyl\Answer\Widget {
    
    private $_exc ;
    
    public function __construct(\Exception $e) {
        parent::__construct();
        $this->_exc = $e ;
    }
    
    public function getContent() {
        echo $this->_exc ;
        return $this->_exc->getMessage() ;
    }
    
}
