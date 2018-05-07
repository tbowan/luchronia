<?php

class Build {
    
    public static $pdo ;
    
    /*
     *  Gestion des paramètres
     */
    
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
    
    /*
     * Initialisation de la base de données
     */
    
    private static function _connect($config) {
        $host = $config["dao"]["hostname"] ;
        $user = $config["dao"]["username"] ;
        $pass = $config["dao"]["password"] ;
        self::$pdo = new PDO("mysql:host={$host};charset=utf8", "{$user}", "{$pass}" );
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    private static function _database($config) {
        $dbname = $config["dao"]["database"] ;
        self::$pdo->query("CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET 'utf8'  DEFAULT COLLATE 'utf8_unicode_ci'") ;
        self::$pdo->query("USE $dbname") ;
    }
    
    private static function _getVersion() {
        try {
            $st = self::$pdo->query('select * from quantyl_config where `key` = "schema_version"') ;
            $row = $st->fetch() ;
            return $row === false ? array() : explode("-", $row["value"]) ;
        } catch (\Exception $e) {
            return array() ;
        }
    }
    
    public static function initialize_database($config) {
        static::_connect($config) ;
        static::_database($config) ;
        return static::_getVersion() ;
    }
    
    /*
     * Application des patches
     */
    
    private static function _extractVersion($f) {
        return intval(preg_replace("/[^0-9].*/", "", $f)) ;
    }
    
    private static function _isNewer($previous, $current) {
        
        foreach ($previous as $i => $p) {
            $c = isset($current[$i]) ? $current[$i] : null ;
            if ($c === null) {
                return null ;
            } else if ($c > $p) {
                return true ;
            } else if ($c < $p) {
                return false ;
            }
        }
        
        if (count($current) == count($previous)) {
            return null ;
        } else {
            return true ;
        }
        
    }
    
    public static function apply_patches($version) {
        print_r($version) ;
        echo "-- Applying Patches\n" ;
        
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR
                . ".." . DIRECTORY_SEPARATOR
                . "structure" ;
        $current = array() ;
        
        self::_apply_directory(realpath($dir), $version, $current, 0) ;
    }
    
    
    private static function _apply_directory($directory, $previous, &$current, $depth) {

        echo "   - Répertoire : $directory\n" ;
        
        $files = scandir($directory) ;
        sort($files, SORT_NUMERIC) ;
        
        foreach ($files as $f) {
            if ($f == "." || $f == ".." || $f == ".svn") {
                continue ;
            }
            
            $v = static::_extractVersion($f) ;
            $current[$depth] = $v ;
            
            $filename = $directory . DIRECTORY_SEPARATOR . $f ;
            $newer = static::_isNewer($previous, $current) ;
            
            if (is_file($filename) && $newer === true) {
                static::apply_patch($filename, $current) ;
            } else if (is_dir($filename) && $newer !== false) {
                static::_apply_directory($filename, $previous, $current, $depth+1) ;
            }
            
        }
        
        unset($current[$depth]) ;
        
    }
    
    private static function apply_patch($filename, $version = null) {
        echo "   - File       : $filename\n" ;
        switch (substr($filename, -4)) {
            case ".php" :
                self::apply_patch_php($filename, $version) ;
                break ;
            case ".sql" :
                self::apply_patch_sql($filename, $version) ;
                break ;
            default:
                echo "Unrecognized extension for $filename, skipping\n" ;
                return false ;
        }
        self::setVersion($version) ;
        return true ;
    }
        
    public static function setVersion($version = null) {
        if ($version != null) {
            $str = implode("-", $version) ;
            $st = self::$pdo->prepare('update quantyl_config set `value` = :v where `key` = "schema_version"') ;
            $st->execute(array("v" => $str)) ;
        }
    }
    
    public static function apply_patch_php($filename, $version) {
        include($filename) ;
        return true ;
    }
    
    public static function apply_patch_sql($filename, $version) {
        $query = file_get_contents($filename);
        $st = self::$pdo->query($query) ;
        if ($st !== false) {
            $st->closeCursor() ;
        }
        return true ;
    }
    
    /*
     * Application des patches
     */
    
    public static function setupI18N() {
        echo "-- Applying translations\n" ;
        
        // Step 1 : finding patches
        $patch_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR
                . ".." . DIRECTORY_SEPARATOR
                . "i18n" . DIRECTORY_SEPARATOR ;
        $files = scandir($patch_dir) ;
        
        // Step 2 : applying them
        foreach ($files as $f) {
            if (is_file($patch_dir . $f)) {
                self::apply_patch($patch_dir . $f) ;
            }
        }
    }
    
    public static function setupDumps() {
        echo "-- Applying Energy\n" ;
        
        // Step 1 : finding patches
        $patch_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR
                . ".." . DIRECTORY_SEPARATOR
                . "dump" . DIRECTORY_SEPARATOR ;
        $files = scandir($patch_dir) ;
        
        // Step 2 : applying them
        foreach ($files as $f) {
            if (is_file($patch_dir . $f)) {
                self::apply_patch($patch_dir . $f) ;
            }
        }
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
        $config = parse_ini_file($args["-config"], true) ;
        $version = self::initialize_database($config) ;
        
        self::apply_patches($version) ;
        self::setupI18N() ;
        self::setupDumps() ;
        
    }
    
    
}

Build::main($argv) ;