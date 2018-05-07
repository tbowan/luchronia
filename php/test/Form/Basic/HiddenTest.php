<?php

use \Form\Basic\Hidden as Input ;

class HiddenTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new Input() ;
        $this->assertNull($input->getValue()) ;

        $otherInput = new Input("a") ;
        $this->assertEquals($otherInput->getValue(), "a") ;
        
        return $otherInput ;
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
