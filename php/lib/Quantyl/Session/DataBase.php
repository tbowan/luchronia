<?php

namespace Quantyl\Session ;

class DataBase extends Session implements \SessionHandlerInterface {
    
    private $_pdo ;
    private $_table ;
    
    public function __construct($pdo, $table) {
        $this->_pdo = $pdo ;
        $this->_table = $table ;
    }
    
    public function close() {
        // doesn't have to close pdo
        // done by php while destroying object
        // (and allow other use of this pdo to survive session closing)
        return true ;
    }
    
    public function destroy($session_id) {
        $query = "delete"
                . " from `" . $this->_table . "`"
                . " where `session_id` = :session_id" ;
        $st = $this->_pdo->prepare($query) ;
        $st->execute(array("session_id" => $session_id)) ;
    }
    
    public function gc($maxtime) {
        $query = "delete"
                . " from `" . $this->_table . "`"
                . " where `session_time` + `lifetime` < :session_time" ;
        $st = $this->_pdo->prepare($query) ;
        $st->execute(array("session_time" => time() - $maxtime)) ;
    }
    
    public function open($save_path, $name) {
        // doesn't have to open any connexion : we use pdo
        return true ;
    }
    
    private function _getData($session_id) {
        $query = "select session_data"
                . " from `" . $this->_table . "`"
                . " where `session_id` = :session_id" ;
        $st = $this->_pdo->prepare($query) ;
        $st->execute(array("session_id" => $session_id)) ;
        
        $row = $st->fetch() ;
        return ($row === false ? null : $row["session_data"]) ;
        
    }
    
    public function read($session_id) {
        
        $data = $this->_getData($session_id) ;
        
        return ($data === null ? "" : $data) ;
    }
    
    public function write($session_id, $session_data) {
        
        $data = $this->_getData($session_id) ;
        
        if ($data === null) {
                $query = "insert "
                    . " into `" . $this->_table . "`"
                    . " (`session_id`, `session_data`, `session_time`) values"
                    . " (:session_id , :session_data , :session_time)" ;
        } else {
                $query = "update "
                    . " `" . $this->_table . "`"
                    . " set"
                    . " `session_data` = :session_data, "
                    . " `session_time` = :session_time"
                    . " where `session_id` = :session_id" ;
        }
        $st = $this->_pdo->prepare($query) ;
        $st->execute(array(
                "session_id"   => $session_id,
                "session_data" => $session_data,
                "session_time" => time()
                    )) ;
        return true ;
    }
    
    public static function createHandler(\Quantyl\Server\Server $srv) {
        $cfg   = $srv->getConfig() ;
        $table = $cfg["session.tablename"] ;
        $pdo   = $srv->getPDO() ;
        
        return new Database($pdo, $table) ;
    }
    
    public function _setLong() {
        $session_id = session_id() ;
        $duration = 72 * 60 * 60 ;
        
        $data = $this->_getData($session_id) ;
        if ($data === null) {
            $query = ""
                    . " insert into `" . $this->_table . "`"
                    . " (session_id, lifetime)"
                    . " values (:session_id, :lifetime)" ;
        } else {
            $query = ""
                    . " update `" . $this->_table . "`"
                    . " set `lifetime` = :lifetime"
                    . " where `session_id` = :session_id" ;
        }
        
        $st = $this->_pdo->prepare($query) ;
        $st->execute(array(
                "session_id"    => $session_id,
                "lifetime"      => $duration
                    )) ;
        setcookie("QUASESSID", $session_id, time() + $duration, "/") ;
    }
    
    public function _checkLong() {
        $session_id = session_id() ;
        $quasessid = (isset($_COOKIE["QUASESSID"]) ? $_COOKIE["QUASESSID"] : null) ;
        $data = $this->read($quasessid) ;
        if ($data != "" && $quasessid != $session_id) {
            
            session_regenerate_id(true) ;
            session_decode($data) ;
            $this->_setLong() ;
            
            $this->destroy($quasessid) ;
        }
    }
    
}
