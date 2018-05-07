<?php

class DumpEnergy {
    
    private static $_pdo ;
    
    public static function parse_args($args, &$specs) {
        for ($i=1; $i < count($args); $i++) {
            $arg = $args[$i] ;
            if (array_key_exists($arg, $specs) && $i + 1 < count($args)) {
                $k = $arg ;
                $i++ ;
                $specs[$k] = $args[$i] ;
            }
        }
        return ;
    }
    
    
    public static function dump() {
        echo "Dumping Energy Cost\n" ;
        
        $content = "" ;
        
        $st = self::$_pdo->prepare("select name, energy from game_ressource_item") ;
        $st->execute() ;
        
        foreach ($st as $row) {
            $content .= "update game_ressource_item set energy = {$row["energy"]} where name = \"{$row["name"]}\" ;\n" ;
        }
        
        return $content ;
    }
    
    public static function init($config) {
        $dbname = $config["dao"]["database"] ;
        $host   = $config["dao"]["hostname"] ;
        $user   = $config["dao"]["username"] ;
        $pass   = $config["dao"]["password"] ;
        
        $pdo = new PDO(
                "mysql:dbname={$dbname};host={$host};charset=utf8",
                "{$user}",
                "{$pass}"
                );
        
        self::$_pdo              = $pdo ;
    }
    
    public static function main($argv) {
        
        // Parse args
        
        $args = array ("-config" => null) ;
        self::parse_args($argv, $args) ;
        if ($args["-config"] == null) {
            echo "Must specify configuration file -config\n" ;
            exit (1) ;
        }
        // setup pdo
        self::init(parse_ini_file($args["-config"], true)) ;
        
        $content = self::dump() ;
        
        $dest_file = ""
                . dirname(__FILE__) . DIRECTORY_SEPARATOR
                . "dump" . DIRECTORY_SEPARATOR
                . "energy.sql" ;
        
        file_put_contents($dest_file, $content) ;
    }
    
}

DumpEnergy::main($argv) ;


?>
