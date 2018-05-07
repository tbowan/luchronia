<?php

use \Form\Basic\TextArea as Input ;

class TextAreaTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new Input() ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(Input $input) {

        $input->parseValue("dummy") ;
        $this->assertEquals($input->getValue(), "dummy") ;
        
        $input->parseValue("<p>Bonjour</p>") ;
        $this->assertEquals($input->getValue(), "&lt;p&gt;Bonjour&lt;/p&gt;") ;
         
        return $input ;
    }

}
