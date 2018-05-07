<?php

namespace Quantyl\Exception\Http ;

class ClientUnauthorized extends HttpException {
    
    public function __construct($message = null, $previous = null) {
        parent::__construct($message, 401, $previous) ;
    }
    
}
