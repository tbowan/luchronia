<?php

use \Form\Basic\Email as Input ;

class EmailTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new Input() ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(Input $input) {

        $input->parseValue("moi@example.com") ;
        $this->assertEquals($input->getValue(), "moi@example.com") ;
        
        return $input ;
    }

    /**
     * @depends test_init
     * @expectedException \Exception
     */
    public function test_invalid(Input $input) {
        $input->parseValue("dummy") ;
    }

}
