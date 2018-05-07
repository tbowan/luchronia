<?php

use \Form\Basic\Password as Input ;

class PasswordTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new Input() ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(Input $input) {

        $input->parseValue("<p>Test") ;
        $this->assertEquals($input->getValue(), "<p>Test") ;
        
        return $input ;
    }

}
