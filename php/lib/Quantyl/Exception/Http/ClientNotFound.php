<?php

namespace Quantyl\Exception\Http ;

class ClientNotFound extends HttpException {
    
    public function __construct($message = null, $previous = null) {
        parent::__construct($message, 404, $previous) ;
    }
    
}
