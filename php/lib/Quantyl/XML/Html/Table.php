<?php

namespace Quantyl\XML\Html ;

use \Quantyl\XML\Raw ;

class Table extends \Quantyl\XML\Base {
    
    private $_rows ;
    
    public function __construct() {
        parent::__construct("table") ;
        $this->_rows = 0 ;
    }
    
    public function addHeaders($headers) {
        $row = $this->addChild(new TR()) ;
        foreach ($headers as $h) {
            $row->addChild(new TH())
                ->addChild(new Raw($h)) ;
        }
        
        return $row ;
    }
    
    public function addRow($elements) {
        $row = $this->addChild(new TR()) ;
        foreach ($elements as $h) {
            $row->addChild(new TD())
                ->addChild(new Raw($h)) ;
        }
        $this->_rows++ ;
        return $row ;
    }
    
    public function getRowsCount() {
        return $this->_rows ;
    }
    
}

?>
