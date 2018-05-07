<?php

namespace Model\Stats ;

class Script extends \Quantyl\Dao\BddObject {
    
    public function getEnd() {
        
        if ($this->end != null) {
            return $this->end ;
        } else if ($this->percent != 0) {
            $dt = time() - $this->start ;
            $duration = 100 * $dt / $this->percent ;
            $end = $this->start + $duration ;
            return $end ;
        } else {
             return null ;
        }
        
    }
    
    
    public static function GetScripts() {
        $rows = self::getStatement(""
                . "select script"
                . " from `" . self::getTableName() . "`"
                . " group by script",
                array()) ;
        $res = array() ;
        foreach ($rows as $st) {
            $res[] = $st["script"] ;
        }
        return $res ;
    }
    
    public static function GetHosts() {
        $rows = self::getStatement(""
                . "select hostname"
                . " from `" . self::getTableName() . "`"
                . " group by hostname",
                array()) ;
        $res = array() ;
        foreach ($rows as $st) {
            $res[] = $st["hostname"] ;
        }
        return $res ;
    }
    
    public static function GetHostAndScripts() {
        $rows = self::getStatement(""
                . "select script, hostname"
                . " from `" . self::getTableName() . "`"
                . " group by hostname, script",
                array()) ;
        $res = array() ;
        foreach ($rows as $st) {
            $res[] = array ($st["script"], $st["hostname"]) ;
        }
        return $res ;
    }
    
    public static function GetByHostAndScript($hostname, $script) {
        return self::getResult(""
                . "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     script = :script and"
                . "     hostname = :hostname",
                array(
                    "script" => $script,
                    "hostname" => $hostname
                )) ;
    }
    
    public static function GetLastDone($hostname, $script) {
        return self::getSingleResult(""
                . "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     script = :script and"
                . "     hostname = :hostname and"
                . "     not isnull(end)"
                . " order by `end` desc"
                . " limit 1",
                array(
                    "script" => $script,
                    "hostname" => $hostname
                )) ;
    }
    
    public static function GetLastRun() {
        return self::getResult(""
                . "select *"
                . " from `" . self::getTableName() . "` as a"
                . " join ("
                . "     select script, hostname, max(start) as start"
                . "     from `" . self::getTableName() . "`"
                . "     group by script, hostname"
                . " ) as b on a.script = b.script and a.hostname = b.hostname and a.start = b.start",
                array()) ;
    }
    
    public static function GetRunning() {
        return self::getResult(""
                . "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     isnull(end)"
                . " order by `start` desc",
                array()) ;
    }
    
    public static function GetByHostname($hostname) {
        return self::getResult(""
                . "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `hostname` = :hostname"
                . " order by `start` desc",
                array("hostname" => $hostname)) ;
    }
    
    public static function GetByScript($script) {
        return self::getResult(""
                . "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `script` = :script"
                . " order by `start` desc",
                array("script" => $script)) ;
    }
    
    public function isUp() {
        return
            $this->pid                               != 0       &&
            shell_exec("ps -p {$this->pid} -o pid=") != null    ;
    }
    
    public function killMe() {
        posix_kill($this->pid, SIGTERM) ;
    }
    
}
