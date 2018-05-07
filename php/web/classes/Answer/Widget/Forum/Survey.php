<?php

namespace Answer\Widget\Forum ;

class Survey extends \Quantyl\Answer\Widget {
    
    private $_thread ;
    private $_show ;
    
    public function __construct(\Model\Forum\Thread $th, $show) {
        $this->_thread = $th ;
        $this->_show   = $show ;
    }
    
    public function showResults() {
        $choices = $this->_thread->getChoices() ;
        if (count($choices) > 0) {
            $table = new \XML\Html\Table() ;
            $table->addHeaders(array(\I18n::CHOICE(), \I18n::RESULT())) ;
            foreach ($choices as $c) {
                $table->addRow(array(
                    $c->message,
                    $c->getScore()
                )) ;
            }
            return "$table" ;
        } else {
            return "" ;
        }
    }
    
    public function showForm() {
        $choices = $this->_thread->getChoices() ;
        $radiochoices = array() ;
        foreach ($choices as $c) {
            $radiochoices[$c->getId()] = $c->message ;
        }
        if (count($radiochoices) > 0) {
            $form = new \Quantyl\Form\Form() ;
            
            $form->setAction("/Forum/Vote?thread={$this->_thread->id}") ;
            
            
            
            $form->addInput("choice", new \Quantyl\Form\Fields\Radio(\I18n::VOTE(), true)) ;
            $form->addInput("send", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
            
            return $form->getContent() ;
        } else {
            return "" ;
        }
    }
    
    public function getContent() {
        
        if ($this->_show) {
            return $this->showResults() ;
        } else {
            return $this->showForm() ;
        }
    }
    
}
