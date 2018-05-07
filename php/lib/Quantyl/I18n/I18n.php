<?php

namespace Quantyl\I18n ;

class I18n {
    
    private static $_theTranslator ;
    
    public static function getInstance() {
        if (self::$_theTranslator === null) {
            self::$_theTranslator = new NullTranslator() ;
        }
        return self::$_theTranslator ;
    }
    
    public static function setInstance(Translator $instance) {
        self::$_theTranslator = $instance ;
    }
    
    public static function setLang($lang) {
        $translator = self::getInstance() ;
        return $translator->setLang($lang) ;
    }
    
    public static function translate($key, $params = array()) {
        $translator = self::getInstance() ;
        return $translator->translate($key, $params) ;
    }
    
    public static function _date_time($timestamp) {
        $format = static::translate("_format_date_time") ;
        return gmdate($format, $timestamp) ;
    }
    
    public static function _date($timestamp) {
        $format = static::translate("_format_date") ;
        return gmdate($format, $timestamp) ;
    }
    
    public static function _time($timestamp) {
        $format = static::translate("_format_time") ;
        // Gmdate pour ne pas avoir une heure de plus à cause des heures d'été
        return gmdate($format, $timestamp) ;
    }
    
    public static function _time_delay($timestamp) {
        $format = static::translate("_format_time_delay") ;
        // Gmdate pour ne pas avoir une heure de plus à cause des heures d'été
        return gmdate($format, $timestamp) ;
    }
    
    public static function __callStatic($name, $arguments) {
        return self::translate($name, $arguments) ;
    }
    
}
