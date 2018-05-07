<?php

namespace Quantyl\Exception\Http ;

class ClientForbidden extends HttpException {
    
    public function __construct($message = null, $previous = null) {
        parent::__construct($message, 403, $previous) ;
    }
    
}
