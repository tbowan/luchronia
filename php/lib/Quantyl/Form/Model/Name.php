<?php

namespace Quantyl\Form\Model ;

// TODO : vérifier que la table implémente les bonnes méthodes

class Name extends \Quantyl\Form\Fields\Text {
    
    private $_table ;
    
    public function __construct(
            \Quantyl\Dao\BddTable $table,
            $label = null,
            $mandatory = false,
            $description = null) {
        
        $this->_table = $table ;
        parent::__construct($label, $mandatory, $description);
        
    }
    
    public function parse($value) {
        $name = parent::parse($value) ;
        try {
            $res = $this->_table->GetByName($name) ;
        } catch (\Exception $ex) {
            $res = null ;
        }
        if ($res === null && $this->isMandatory()) {
            throw new \Exception(\I18n::FORM_MODEL_NAME_NO_SUCH_ELEMENT()) ;
        }
        return $res ;
    }
    
    public function unparse($value) {
        return ($value == null ? null : parent::unparse($value->getName()));
    }
}
