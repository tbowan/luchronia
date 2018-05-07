<?php

class Setup {
    
    private static $_nocron ;
    
    private static $_basedir ;
    private static $_sqldir ;
    private static $_i18ndir ;
    private static $_config ;
    
    private static $_phpnext ;
    private static $_sqlnext ;
    
    private static $_phplast ;
    private static $_sqllast ;
    
    
    public static function applyPatch($filename) {
        echo "   - Applying patch $filename ... " ;
        
        if (preg_match("/.*\.php/", $filename) == 1) {
            include $filename ;
        } else {
            $env = \Environment::getInstance();
            $pdo = $env->getPDO();
            $query = file_get_contents($filename);

            /*
             * Plutôt qu'utiliser $pdo->exec($query), qui génère des problèmes
             * 
             * J'utilise query et closecursor qui permettent de libérer la connexion
             * après la requête.
             */

            $st = $pdo->query($query) ;
            $st->closeCursor() ;
        }

        echo "ok\n" ;
    }
    
    public static function setupSQLStructure() {
        echo "-- Setup SQL Structure\n" ;
        
        $files = scandir(self::$_sqldir) ;
        sort($files, SORT_NUMERIC) ;

        foreach ($files as $f) {
            if (is_file(self::$_sqldir . $f)) {
                $patchversion = intval(preg_replace("/[^0-9].*/", "", $f)) ;

                if ($patchversion > self::$_sqllast) {
                    self::applyPatch(self::$_sqldir . $f) ;
                }
            }
        }

        self::$_sqlnext = $patchversion ;
    }
    
    public static function setupSQLTranslation() {
        echo "-- Setup SQL Translation\n" ;
        $files = scandir(self::$_i18ndir) ;
        sort($files, SORT_NUMERIC) ;

        foreach ($files as $f) {
            if (is_file(self::$_i18ndir . $f)) {
                self::applyPatch(self::$_i18ndir . $f) ;
            }
        }
    }
    
    public static function setupSQL() {
        echo "- Setup SQL\n" ;
        self::setupSQLStructure() ;
        self::setupSQLTranslation() ;
    }
    
    public static function setupCron() {
        if (self::$_nocron) {
            return ;
        }
        echo "- Setup Cron\n" ;
        
        $crontab  = "0 * * * * php -f " ;
        $crontab .= self::$_basedir ;
        $crontab .= "/utils/cron/update.php -- " ;
        $crontab .= "-base " . self::$_basedir . " " ;
        
        if (self::$_config != null) {
            $crontab .= "-config " . self::$_config . " " ;
        }
        $crontab .= " 1&2> /dev/null\n" ;
        
        
        $output = shell_exec("echo \"$crontab\" | crontab -") ;
        echo $output ;
    }
    
    public static function writeVersion() {
        $pdo = \Environment::getInstance()->getPDO() ;
        $st = $pdo->prepare("insert into `version` (`php`, `sql`) values (:php, :sql)") ;
        $st->execute(array("php" => self::$_phpnext, "sql" => self::$_sqlnext)) ;
    }
    
    public static function _autoload($classname) {
        $filename  = self::$_basedir ;
        $filename .= "web" . DIRECTORY_SEPARATOR ;
        $filename .= "classes" . DIRECTORY_SEPARATOR ;
        $filename .= str_replace("\\", DIRECTORY_SEPARATOR, $classname);
        $filename .= ".php" ;

        if (is_file($filename)) {
            include_once $filename ;
        }
    }
    
    private static function readArguments($argv) {
        // Init static variables
        self::$_basedir = "" ;
        self::$_phpnext = "-" ;
        self::$_config = null ;
        self::$_nocron = false ;
        
        // Read arguments
        for ($i = 1; $i < count($argv); $i++) {
            switch($argv[$i]) {
                case "-php" :
                    $i++ ; self::$_phpnext = $argv[$i] ;
                    break ;
                case "-base" :
                    $i++ ; self::$_basedir = $argv[$i] . "/" ;
                    break ;
                case "-rel" :
                    $i++ ;
                    self::$_basedir = realpath(getcwd() . "/" . $argv[$i]) . "/" ;
                    break ;
                case "-config" :
                    $i++ ; self::$_config = $argv[$i] ;
                    break ;
                case "-nocron" :
                    self::$_nocron = true ;
                    break ;
                default :
                    ;
            }
        }
        // Init dynamic variable
        self::$_sqldir = self::$_basedir . "SQL/structure/" ;
        self::$_i18ndir = self::$_basedir . "SQL/i18n/" ;
        
        
        // instanciate environment
        spl_autoload_register("Setup::_autoload");
        
        if (self::$_config != null) {
            \Environment::initEnvironment("Cli", self::$_config) ;
        }
        
    }
    
    public static function findLastVersion() {
        $env = \Environment::getInstance() ;
        $pdo = $env->getPDO() ;
        $st = $pdo->prepare("select * from version order by `done` desc limit 1") ;
        try {
            $st->execute() ;
            $res = $st->fetch() ;

            self::$_sqllast = intval($res["sql"]) ;
            self::$_phplast = $res["php"] ;
        } catch (\Exception $e) {
            // no table exists, need to build patch zero
            echo "No Table found - initializing database\n" ;
            
            self::applyPatch(self::$_sqldir . "00.sql") ;
            
            self::$_sqllast = 0 ;
            self::$_phplast = "-" ;
        }
    }
    
    
    public static function main($argv) {
        self::readArguments($argv) ;
        self::findLastVersion() ;
        
        self::setupSQL() ;
        
        self::setupCron() ;
        
        self::writeVersion() ;
    }
}

Setup::main($argv) ;


?>
