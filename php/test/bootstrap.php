<?php

include
    dirname(__FILE__) 
    . DIRECTORY_SEPARATOR . ".." 
    . DIRECTORY_SEPARATOR . "web" 
    . DIRECTORY_SEPARATOR . "classes" 
    . DIRECTORY_SEPARATOR . "bootstrap.php" 
    ;

$server = new Quantyl\Server\CmdLineServer(
        new Quantyl\Configuration\IniConfiguration("config.php"),
        array()
        ) ;

