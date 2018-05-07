<?php

namespace Quantyl\Form ;

class Form extends \Quantyl\Answer\Answer {

    private $_message ;
    private $_data ;
    private $_submits ;
    
    private $_action ;
    private $_method ;
    
    private $_renderer ;
    
    public function __construct($message = null) {
        $this->_message     = $message ;
        $this->_data        = new FieldSet() ;
        $this->_submits     = new FieldSet() ;
        
        $this->_action      = "" ;
        $this->_method      = "" ;
        $this->_renderer    = new Renderer() ;
    }
    
    public function setRendered(Renderer $r) {
        $this->_renderer = $r ;
    }
    
    // Message
    
    public function setMessage($msg) {
        $this->_message = $msg ;
    }
    
    public function addMessage($msg) {
        $this->_message .= $msg ;
    }
    
    public function getMessage() {
        return $this->_message ;
    }
    
    // Actions and method
    
    public function setAction($action, $method = null) {
        $this->_action = $action ;
        $this->_method = $method ;
    }
    
    public function getAction() {
        return $this->_action ;
    }
    
    public function getMethod() {
        return $this->_method ;
    }
    
    // Data and inputs
    
    public function addInput($key, Input $input) {
        return $this->_data->addInput($key, $input) ;
    }
    
    public function getData() {
        return $this->_data->getValue() ;
    }
    
    // Submit and Events
    
    public function addSubmit($key, Fields\Submit $s) {
        return $this->_submits->addInput($key, $s) ;
    }
    
    public function hasSubmit() {
        return $this->_submits->countInputs() > 0 ;
    }
    
    public function getSubmit() {
        $buttons = array() ;
        $value = $this->_submits->getValue() ;

        foreach ($value as $key => $bool) {
            if ($bool) {
                $buttons[] = $this->_submits->getInput($key) ;
            }
        }
        
        if (count($buttons) != 1) {
            // TODO : one button and nothing clicked
            return null ;
        } else {
            return $buttons[0] ;
        }
    }
    
    // Empty form
    
    public function isEmpty() {
        
        return
                $this->_message === null &&
                $this->_data->countInputs() == 0 &&
                ! $this->hasSubmit() ;
    }
    
    // Global process
    
    public function parseValue($data) {
        $this->_data->parseValue($data) ;
        if (isset($data["_submits"])) {
            $this->_submits->parseValue($data["_submits"]) ;
        } else {
            $this->_submits->parseValue(array()) ;
        }
    }
    
    public function filter($data) {
        
        if (count($data) > 0) {
            $this->parseValue($data) ;
        }
        $button = $this->getSubmit() ;
        
        if ($button === null) {
            if ($this->hasSubmit()) {
                // Need a click
                throw new \Exception("") ;
            } else if (! $this->_data->isValid()) {
                // Need a valid form
                throw new \Exception(\I18n::EXP_FORM_ISNOT_VALID()) ;
            } else {
                return $this->getData() ;
            }
        } else {
            if ($button->needValidForm() && ! $this->_data->isValid()) {
                throw new \Exception(\I18n::EXP_FORM_ISNOT_VALID()) ;
            } else {
                $data = $this->getData() ;
                $button->callBack($data, $this) ;
                return $data ;
            }
        }
    }
    
    public function getHTML($action = "", $class = "") {
        
        
        if ($action == "") {
            $action = $this->_action ;
        }
        
        if ($this->_method != "") {
            $method = $this->_method ;
        } else {
            $method = "post" ;
        }
        
        $res  = "<form class=\"QForm $class\" "
                . " method=\"$method\""
                . " action=\"" . addslashes($action) . "\""
                . " accept-charset=\"UTF-8\""
                . " >\n";
        
        // Info : ajouter enctype=\"multipart/form-data\" pour un formulaire acceptant les fichiers
        
        if ($this->_message !== null) {
            $res .= "<div class=\"form_message\" >"
                    . $this->_message
                    . "</div>" ;
        }
        
        $res .= "<div class=\"form_data\">" . $this->_data->getHTML() . "</div>" ;

        $res .= "<div class=\"form_submits\">" ;
        if ($this->hasSubmit()) {
            $res .= $this->_submits->getHtml("_submits") ;
        }
        $res .= "</div>" ;
        
        // $res .= $this->endFormHTML() ;
        $res .= "</form>\n";
        
        return $res;
        
    }

    public function getContent($action = "") {
        return $this->_renderer->render($this, $action) ;
    }

    public function isDecorable() {
        return true ;
    }

}
