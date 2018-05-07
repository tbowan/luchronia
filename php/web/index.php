<?php

require "classes/bootstrap.php" ;

$config = new \Quantyl\Configuration\IniConfiguration("config.php") ;
\Quantyl\Server\Server::Start($config) ;
