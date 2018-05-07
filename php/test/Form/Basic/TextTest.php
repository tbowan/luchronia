<?php

class TextTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new \Form\Basic\Text() ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(\Form\Basic\Text $input) {

        $input->parseValue("dummy") ;
        $this->assertEquals($input->getValue(), "dummy") ;
        
        $input->parseValue("<p>Bonjour</p>") ;
        $this->assertEquals($input->getValue(), "&lt;p&gt;Bonjour&lt;/p&gt;") ;
        
        return $input ;
    }



}
