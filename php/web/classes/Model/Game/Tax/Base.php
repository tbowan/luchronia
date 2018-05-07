<?php

namespace Model\Game\Tax ;

class Base extends \Quantyl\Dao\BddObject {
    
    public function update() {
        if ($this->fix == 0 && $this->var == 0) {
            $this->delete() ;
        } else if (! $this->exists()) {
            $this->create() ;
        } else {
            parent::update() ;
        }
    }
    
    public function delete() {
        if ($this->exists()) {
            parent::delete();
        }
    }
    
    
}
