<?php

$dir = dirname(__FILE__) ;

include "$dir/bootstrap.php" ;

$cfg = new \Quantyl\Configuration\IniConfiguration("$dir/config.php") ;

\Quantyl\Server\Server::Start($cfg) ;

