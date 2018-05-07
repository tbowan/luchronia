<?php

require  dirname(__FILE__) . "/../web/classes/bootstrap.php" ;

function Luchronia_command_autoload($classname)
{

    $filename  = dirname(__FILE__) . DIRECTORY_SEPARATOR ;
    $filename .= str_replace("\\", DIRECTORY_SEPARATOR, $classname);
    $filename .= ".php" ;

    if (is_file($filename)) {
        include_once $filename ;
    }
}

spl_autoload_register("Luchronia_command_autoload");
