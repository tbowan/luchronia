<?php

namespace Quantyl\Exception\Http ;

class ServerInternalError extends HttpException {
    
    public function __construct($message = null, $previous = null) {
        parent::__construct($message, 500, $previous) ;
    }
    
}
