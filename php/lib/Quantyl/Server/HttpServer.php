<?php

namespace Quantyl\Server ;

use \Quantyl\Session\HttpSession ;
use \Quantyl\Request\Request ;


class HttpServer extends Server {
    
    private function stripSlashesRecurs($value) {
        if (is_array($value)) {
            $res = array() ;
            foreach ($value as $k => $v) {
                $res[$k] = $this->stripSlashesRecurs($v) ;
            }
            return $res ;
        } else {
            return stripslashes($value) ;
        }
    }
    
    public function createRequest() {
        if (get_magic_quotes_gpc()) {
            return new Request(
                    $this,
                    $this->stripSlashesRecurs($_GET),
                    $this->stripSlashesRecurs($_POST)
                    ) ;
        } else {
            return new Request(
                    $this,
                    $_GET,
                    $_POST
                    ) ;
        }
    }
    
    public function getReferer() {
        if (isset($_SERVER["HTTP_REFERER"])) {
            return $_SERVER["HTTP_REFERER"];
        } else {
            return $_SERVER["REQUEST_URI"];
        }
    }
    
    public function getClientIp() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    public function getServerHostname() {
         return $_SERVER["HTTP_HOST"] ;
    }

    public function render(\Quantyl\Answer\Answer $a) {
        $a->sendHeaders($this) ;
        echo $a->getContent() ;
    }

    public function header($str, $replace = true, $http_respons = null) {
        header($str, $replace, $http_respons) ;
    }

    
    public static function Factory(\Quantyl\Configuration\Configuration $cfg) {
        return new HttpServer($cfg) ;
    }

}
