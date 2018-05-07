<?php

namespace Model\Game\Building\Jobs ;

class Library extends Base {
    
    protected $_library ;
    
    public function __construct(\Model\Game\Building\Instance $i) {
        parent::__construct($i);
        $this->_library = \Model\Game\Building\Library::GetFromInstance($this->_instance) ;
    }
    
    public function acceptStock(\Model\Game\Ressource\Item $item) {
        $parent = parent::acceptStock($item) ;
        if (! $parent) {
            return false ;
        }
        $parch = \Model\Game\Ressource\Parchment::GetByItem($item) ;
        $book  = \Model\Game\Ressource\Book::GetByItem($item) ;

        return $parch != null || $book != null ;
    }
    
    public function getName() {
        $res = parent::getName() ;
        $res .= " : " . $this->_library->name ;
        return $res ;
    }
}
