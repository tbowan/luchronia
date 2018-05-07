<?php

use \Form\Basic\FilteredHtml as Input ;

class FilteredHtmlTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $input = new Input() ;
        $this->assertNull($input->getValue()) ;

        return $input ;
    }

    /**
     * @depends test_init
     */
    function test_valid(Input $input) {

        $input->parseValue("<p>Bonjour</p>") ;
        $this->assertEquals($input->getValue(), "<p>Bonjour</p>") ;
        
        return $input ;
    }

    // TODO
    // $input->parseValue("<script>blabla</script>") ;

}
