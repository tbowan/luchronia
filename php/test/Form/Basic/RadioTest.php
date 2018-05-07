<?php

use \Form\Basic\Radio as Input ;

class RadioTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new Input() ;
        $input->setChoices(array(0 => "zero", "one" => 1) ) ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(Input $input) {

        $input->parseValue("0") ;
        $this->assertEquals($input->getValue(), 0) ;
        
        $input->parseValue("one") ;
        $this->assertEquals($input->getValue(), "one") ;
        
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
