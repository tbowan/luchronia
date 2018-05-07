<?php

class IntegerTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new \Form\Basic\Integer() ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(\Form\Basic\Integer $input) {

        $input->parseValue("1") ;
        $this->assertEquals($input->getValue(), 1) ;
        $this->assertTrue(is_int($input->getValue())) ;
        
        $input->parseValue(1) ;
        $this->assertEquals($input->getValue(), 1) ;
        $this->assertTrue(is_int($input->getValue())) ;
        
        return $input ;
    }

    /**
     * @depends test_init
     * @expectedException \Exception
     */
    public function test_invalid(\Form\Basic\Integer $input) {
        $input->parseValue("dummy") ;
    }

}

?>
