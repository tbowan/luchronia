<?php

namespace Quantyl\Server ;

use \Quantyl\Configuration\Configuration ;
use \Quantyl\Service\ServiceFactory ;
use \Quantyl\I18n\I18n ;
use \Quantyl\Dao\Dal ;

abstract class Server {
    
    private $_config ;
    private $_srvFact ;
    
    public function __construct(Configuration $config) {
        $this->_config = $config ;
        $this->setDefines() ;
        
        // Initialization
        // - Dal
        Dal::initialize($config) ;
        
        // - I18n
        $translatorClass = $config["I18n.class"] ;
        $translator = new $translatorClass($this) ;
        I18n::setInstance($translator) ;
        
        // - SESSION
        \Quantyl\Session\Session::initSessions($this) ;
        
        $this->_srvFact = new ServiceFactory($this->_config) ;
    }
    
    public function setDefines() {
        $constants = $this->_config["Constant"] ;
        if (is_array($constants)) {
            foreach ($constants as $name => $value) {
                define($name, $value) ;
            }
        }
    }
    
    public function getPDO() {
        return Dal::getPdo() ;
    }
    
    public function getConfig() {
        return $this->_config ;
    }
    
    protected abstract function createRequest() ;
    
    public abstract function getReferer() ;
    
    public abstract function getClientIp() ;
    
    public abstract function getServerHostname() ;
    
    public abstract function render(\Quantyl\Answer\Answer $a) ;
    
    public abstract function header($str, $replace = true, $http_respons = null) ;
    
    // Run and serve request

    public function run() {
        try {
            $request     = $this->createRequest() ;
            $servicename = $request->getServiceName() ;
            $service     = $this->_srvFact->createServiceFromName($servicename) ;
            $response    = $service->serves($request) ;
        } catch (\Quantyl\Exception\Http\HttpException $ex) {
            $cfg        = $this->getConfig() ;
            $widget     = $cfg["view.error"] ;
            $response   = new $widget($ex) ;
        } catch (\Exception $ex) {
            $cfg        = $this->getConfig() ;
            $widget     = $cfg["view.error"] ;
            $response   = new $widget(new \Quantyl\Exception\Http\ServerInternalError($ex)) ;
        }
        $this->render($response) ;
    }

    
    private static $_instance = null;
    
    public static function getInstance() {
        return self::$_instance ;
    }
    
    public static function initInstance(Configuration $cfg) {
        if (self::$_instance !== null) {
            // TODO better exception
            throw new \Exception("Server Already Started") ;
        } else {
            $classname = $cfg["Server.class"] ;
            self::$_instance = $classname::Factory($cfg) ;
        }
    }
    
    public static function Start(Configuration $cfg) {
        self::initInstance($cfg) ;
        self::$_instance->run() ;
    }
    
    public abstract static function Factory(Configuration $cfg) ;
    
    protected function decorate($response, $servicename) {
        if ($response->isDecorable()) {
            $classname = $this->getDecoratorName($servicename) ;
            $answer = new $classname($response) ;
        } else {
            $answer = $response;
        }
        return $answer ;
    }
    
    private function getDecoratorName($servicename) {
        $cfg = $this->getConfig() ;
        
        $last_path = $cfg["view.decoraror"] ;
        $last_classname = $last_path . "\\" . "Main" ;
        
        foreach (explode("/", $servicename) as $part) {
            $last_path = $last_path . "\\" . $part ;
            if (class_exists($last_path)) {
                $last_classname = $last_path ;
            }
        }
        return $last_classname ;
    }
    
    
}
