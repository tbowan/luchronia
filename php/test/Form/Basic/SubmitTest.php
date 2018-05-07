<?php

use \Form\Basic\Submit as Input ;

class SubmitTest extends PHPUnit_Framework_TestCase {

    public function test_enabled_init() {
        $input = new Input("key") ;
        $this->assertFalse($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_enabled_init
     */
    function test_enabled_notclicked(Input $input) {

        $input->parseValue(null) ;
        $this->assertFalse($input->getValue()) ;

        return $input ;
    }

    
    /**
     * @depends test_enabled_init
     */
    function test_enabled_clicked(Input $input) {

        $input->parseValue("key") ;
        $this->assertTrue($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_enabled_init
     * @expectedException \Exception
     */
    function test_enabled_otherValue(Input $input) {
        $input->parseValue("dummy") ;
    }

    
    
    
    public function test_disabled_init() {
        $input = new Input("key", false) ;
        $this->assertFalse($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_disabled_init
     */
    function test_disabled_notclicked(Input $input) {

        $input->parseValue(null) ;
        $this->assertFalse($input->getValue()) ;

        return $input ;
    }

    
    /**
     * @depends test_disabled_init
     * @expectedException \Exception
     */
    function test_disabled_clicked(Input $input) {

        $input->parseValue("key") ;

    }

    /**
     * @depends test_disabled_init
     * @expectedException \Exception
     */
    function test_disabled_otherValue(Input $input) {
        $input->parseValue("dummy") ;
    }
    
    

}

?>
