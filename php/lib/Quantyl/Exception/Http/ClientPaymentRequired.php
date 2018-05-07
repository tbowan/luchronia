<?php

namespace Quantyl\Exception\Http ;

class ClientPaymentRequired extends HttpException {
    
    public function __construct($message = null, $previous = null) {
        parent::__construct($message, 402, $previous) ;
    }
    
}
