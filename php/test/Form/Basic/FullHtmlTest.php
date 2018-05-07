<?php

class FullHtmlTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new \Form\Basic\FullHtml() ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(\Form\Basic\FullHtml $input) {

        $input->parseValue("<p>Bonjour</p>") ;
        $this->assertEquals($input->getValue(), "<p>Bonjour</p>") ;
        
        return $input ;
    }



}

?>
