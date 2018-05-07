<?php

namespace Quantyl\Service ;

use Exception;
use Quantyl\Answer\Redirect;
use Quantyl\Exception\Http\ClientBadRequest;
use Quantyl\Form\Form;
use Quantyl\Request\Request;


abstract class EnhancedService {

    private $_params ;
    private $_answer ;
    private $_caller ;
    private $_request ;

    public final function __get($name) {
        if (isset($this->_params[$name])) {
            return $this->_params[$name] ;
        } else {
            return null ;
        }
    }
    
    public final function __set($name, $value) {
        $this->_params[$name] = $value ;
    }
    
    public final function get($path) {
        $cpn = explode(".", $path) ;
        $res = $this->_params ;
        foreach ($cpn as $key) {
            $res = $res[$key] ;
        }
        return $res ;
    }
    
    public final function set($path, $value) {
        $cpn = explode(".", $path) ;
        $res = $this->_params ;
        for ($i = 0; $i < count($cpn) - 1; $i++) {
            $res = $res[$cpn[$i]] ;
        }
        $res[$cpn[count($cpn)]] = $value ;
    }
    
    public final function getRequest() {
        return $this->_request ;
    }
    
    public final function getAnswer() {
        return $this->_answer ;
    }
    
    public final function setAnswer(\Quantyl\Answer\Answer $a) {
        $this->_answer = $a ;
    }
    
    // Main method : serves request
    
    public final function serves(Request $req) {
        $this->_request = $req ;
        $this->_caller  = $req->getReferer() ;
        $this->initialize($req) ;
        $this->checkPermission($req) ;
        $this->serveData($req) ;
        $this->conclude($req) ;
        
        return $this->getAnswer() ;
    }
    
    // Step 1 : initialization
    
    public final function initialize(Request $req) {
        try {
            $form = new Form() ;
            $this->fillParamForm($form) ;
            $this->_params = $form->filter($req->getParameters()) ;
            $this->init() ;
        } catch (\Exception $e) {
            throw new ClientBadRequest($e->getMessage()) ;
        }
    }
    
    public function fillParamForm(Form &$form) {
        ;
    }
    
    public function init() {
        ;
    }
    
    // Step 2 : check permissions
    
    public function checkPermission(Request $req) {
        ;
    }
    
    // Step 3 : serves data
    
    public final function getDataForm() {
        $form = new Form() ;
        $this->fillDataForm($form) ;
        if (! $form->isEmpty() && ! $form->hasSubmit()) {
            $this->addSubmit($form) ;
        }
        return $form ;
    }
    
    public final function serveData(Request $req) {
        $form = $this->getDataForm() ;
        if (! $form->isEmpty()) {
            $this->manageForm($form, $req) ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        return ;
    }
    
    public function addSubmit(Form &$form) {
        $form->addSubmit("cancel", new \Quantyl\Form\Fields\Submit(\I18n::SUBMIT_CANCEL(), false))
             ->setCallBack($this, "onCancel") ;
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SUBMIT_PROCEED()))
             ->setCallBack($this, "onProceed") ;
    }

    public final function manageForm($form, Request $req) {
        $form->addInput("_referer", new \Quantyl\Form\Fields\Caller($this->_caller)) ;
        
        try {
            $data = $form->filter($req->getData()) ;
            $this->_caller  = $data["_referer"] ;
        } catch (Exception $ex) {
            $this->setAnswer($this->getFormAnswer($ex->getMessage(), $form) );
        }
    }
    
    private function getDecoratorName($servicename) {
        $cfg = $this->getRequest()->getServer()->getConfig() ;
        
        $last_path = $cfg["view.decorator"] ;
        $last_classname = $last_path . "\\" . "Main" ;
        
        foreach (explode("/", $servicename) as $part) {
            $last_path = $last_path . "\\" . $part ;
            if (class_exists($last_path)) {
                $last_classname = $last_path ;
            }
        }
        return $last_classname ;
    }
    
    public function getFormAnswer($msg, $form) {
        return $this->decorate($msg . $form->getContent()) ;
    }
    
    public function decorate($msg) {
        $service   = $this->getRequest()->getServiceName() ;
        $decorator = $this->getDecoratorName($service) ;
        return new $decorator($this, $msg) ;
    }
    
    public function onCancel($data, $form) {
        return null ;
    }
    
    public function onProceed($data, $form) {
        return null ;
    }
    
    // Step 4 : conclusion
    
    private final function onNullAnswer() {
        $a = $this->getAnswer() ;
        if ($a === null) {
            $temp = $this->getView() ;
            if ($temp === null) {
                $temp = new Redirect($this->_caller) ;
            }
            $this->setAnswer($temp) ;
        }
    }
    
    private final function onWidgetAnswer() {
        $a = $this->getAnswer() ;
        if ($a instanceof \Quantyl\Answer\Widget) {
            $this->setAnswer($this->decorate($a)) ;
        }
    }
    
    public final function conclude(Request $req) {
        $this->onNullAnswer() ;
        $this->onWidgetAnswer() ;
    }
    
    public function getView() {
        return null ;
    }
    
    public function getTitle() {
        // get classname and translate it
        $classname = get_class($this) ;
        $key = "TITLE_" . str_replace("\\", "_", $classname) ;
        return \I18n::translate($key) ;
    }

    public function getSubTitle() {
        // get classname and translate it
        $classname = get_class($this) ;
        $key = "SUBTITLE_" . str_replace("\\", "_", $classname) ;
        return \I18n::translate($key) ;
    }
    
}
