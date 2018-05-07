<?php

namespace Answer\View ;

class Exception extends Base {
    
    private $_ex ;
    
    public function __construct(\Quantyl\Exception\Http\HttpException $ex) {
        parent::__construct(null) ;
        $this->_ex = $ex ;
    }
    
    public function getTplContent() {
        $res = "" ;
        
        $res .= new \Answer\Widget\Misc\Section(
                $this->_ex->getName(),
                null,
                null,
                $this->_ex->getDescription(). $this->_ex->getMessage(),
                "") ;
        return $res ;
    }

    public function getTplMenu() {
        return \I18n::LM_MAIN() ;
    }
    
    public function getTplTitle() {
        return \I18n::ERROR() . " - " . $this->_ex->getCode() ;
    }
    
    public function getTplSubTitle() {
        return $this->_ex->getName() ;
    }
    
    public function getTplHeaderImage() {
        return "<img src=\"/style/images/error-" . $this->_ex->getCode() . ".png\" />" ;
    }

}
