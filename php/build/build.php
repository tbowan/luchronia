<?php

class Build {

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
    
    
    public static function usage() {
        echo "Script d'installation du site\n" ;
        echo " Options :\n" ;
        echo "\n" ;
        echo "  -web-config <file>\n" ;
        echo "          Spécifie le fichier de configuration pour la partie web\n" ;
        echo "\n" ;
        echo "  -cmd-config <file>\n" ;
        echo "          Spécifie le fichier de configuration pour les exécutions\n" ;
        echo "          en ligne de commande\n" ;
        echo "\n" ;
        echo "  -crontab <file>\n" ;
        echo "          Spécifie le fichier cron à installer\n" ;
        echo "\n" ;
    }
    
    public static function copy_web_config($source) {
        $base_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR ;
        
        $destination = $base_dir
                . ".." . DIRECTORY_SEPARATOR
                . "web" . DIRECTORY_SEPARATOR
                . "config.php" ;
        
        echo "Copying Web configuration file :\n" ;
        echo " - FROM : " . $source . "\n" ;
        echo " - TO   : " . $destination . "\n" ;
        copy($source, $destination) ;
    }
    
    public static function copy_cmd_config($source) {
        $base_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR ;
        
        $destination = $base_dir
                . ".." . DIRECTORY_SEPARATOR
                . "cmd" . DIRECTORY_SEPARATOR
                . "config.php" ;
        
        echo "Copying Cmd configuration file :\n" ;
        echo " - FROM : " . $source . "\n" ;
        echo " - TO   : " . $destination . "\n" ;
        copy($source, $destination) ;
    }
    
    public static function init_cron($file) {
        echo "Initializing Crontab\n" ;
        
        $basedir = dirname(realpath($file)) ;
        
        $data = parse_ini_file($file, true) ;
        
        $crontab = "" ;
        foreach ($data as $name => $params) {
            echo " - Task : $name\n" ;
            
            $cmd = str_replace("BASEDIR", $basedir, $params["command"]) ;
            
            $crontab .= escapeshellarg(
                    $params["iteration"] . " " .
                    $cmd . "\n"
                ) ;
        }

        $output = shell_exec("echo $crontab | crontab -") ;
        echo $output ;
        
    }
    
    public static function main($argv) {
        
        $args = array (
            "-web-config" => null,
            "-cmd-config" => null,
            "-crontab" => null,
            ) ;
        
        self::parse_args($argv, $args) ;
        
        if ($args["-web-config"] !== null) {
            self::copy_web_config($args["-web-config"]) ;
        }
        
        if ($args["-cmd-config"] !== null) {
            self::copy_cmd_config($args["-cmd-config"]) ;
        }
        
        if ($args["-crontab"] !== null) {
            self::init_cron($args["-crontab"]) ;
        }

    }
    
}

Build::main($argv) ;