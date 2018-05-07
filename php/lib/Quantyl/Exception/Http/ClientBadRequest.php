<?php

namespace Quantyl\Exception\Http ;

class ClientBadRequest extends HttpException {
    
    public function __construct($message = null, $previous = null) {
        parent::__construct($message, 400, $previous) ;
    }
    
}
