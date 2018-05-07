<?php

class DumpI18N {
    
    private static $_pdo ;
    private static $_langtable ;
    private static $_translationtable ;
    
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
    
    public static function getRowStatement($code) {
        $query = "select * from"
                . " `" . self::$_translationtable. "` as t,"
                . " `" . self::$_langtable. "` as l"
                . " where"
                . "  l.code = :code and"
                . "  t.lang = l.id"
                . " order by `key`" ;
        $st = self::$_pdo->prepare($query) ;
        $st->execute(array("code" => $code)) ;
        return $st ;
    }
    
    public static function dumpLang($code) {
        echo "Dumping {$code} :\n" ;
        
        $content = "insert ignore"
                . " into `" . self::$_langtable. "`"
                . " (`code`) values"
                . " ('{$code}') ;\n" ;
                
        $content .= "select @lid := id"
                . " from `" . self::$_langtable. "`"
                . " where code = '{$code}' ;\n" ;
        
        $content .= "\n" ;
        
        $content .= "INSERT INTO `" . self::$_translationtable. "`"
                . " (`key`, `lang`, `translation`)"
                . " VALUES\n" ;
        $lines = array() ;
        foreach (self::getRowStatement($code) as $row) {
            $l = "('" ;
            $l .= addslashes($row["key"]) ;
            $l .= "', @lid, '" ;
            $l .= addslashes($row["translation"]) ;
            $l .= "')" ;

            $lines[] = $l ;
        }
        $content .= implode(",\n", $lines) ;
        $content .= "\nON DUPLICATE KEY UPDATE `translation`= VALUES(`translation`) ;\n" ;

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
        self::$_langtable        = $config["I18N"]["langtable"];
        self::$_translationtable = $config["I18N"]["translatetable"];
    }
    
    public static function main($argv) {
        
        // Parse args
        
        $args = array ("-config" => null, "-code" => null) ;
        self::parse_args($argv, $args) ;
        if ($args["-config"] == null) {
            echo "Must specify configuration file -config\n" ;
            exit (1) ;
        } else if ($args["-code"] == null) {
            echo "Must specify lang code -code\n" ;
            exit (1) ;
        }
        
        // setup pdo
        self::init(parse_ini_file($args["-config"], true)) ;
        
        $content = self::dumpLang($args["-code"]) ;
        
        $dest_file = ""
                . dirname(__FILE__) . DIRECTORY_SEPARATOR
                . "i18n" . DIRECTORY_SEPARATOR
                . $args["-code"] . ".sql" ;
        
        file_put_contents($dest_file, $content) ;
    }
    
}

DumpI18N::main($argv) ;


?>
