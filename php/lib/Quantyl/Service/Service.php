<?php

namespace Quantyl\Service ;

use \Quantyl\Request\Request ;

interface Service {
    
    public abstract function serves(Request $req) ;
    
}
