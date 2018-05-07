<?php

use \Misc\Matrix ;

class MatrixTest extends PHPUnit_Framework_TestCase {
    
    public function test_create() {
        $m = Matrix::create(array(
            array(1, 2),
            array(3, 4),
            array(5, 6)
        )) ;
        
        list($l, $c) = $m->getDimension() ;
        
        $this->assertEquals($l, 3) ;
        $this->assertEquals($m->getLines(), 3) ;
        $this->assertEquals($c, 2) ;
        $this->assertEquals($m->getColumns(), 2) ;
        
        $this->assertEquals($m->getValue(0, 0), 1) ;
        $this->assertEquals($m->getValue(0, 1), 2) ;
        $this->assertEquals($m->getValue(1, 0), 3) ;
        $this->assertEquals($m->getValue(1, 1), 4) ;
        $this->assertEquals($m->getValue(2, 0), 5) ;
        $this->assertEquals($m->getValue(2, 1), 6) ;
        
        $m->setValue(0, 0, 7) ;
        $this->assertEquals($m->getValue(0, 0), 7) ;
        
    }
    
    public function test_equals() {
        $m1 = Matrix::create(array(
            array(1, 2),
            array(3, 4),
            array(5, 6)
        )) ;
        
        $m2 = Matrix::create(array(
            array(1, 2),
            array(3, 4),
            array(5, 6)
        )) ;
        
        $this->assertTrue($m1->equals($m2)) ;
        $this->assertTrue($m2->equals($m1)) ;
        
        for ($i=0; $i<3; $i++) {
            for ($j=0; $j<2; $j++) {
                $v = $m1->getValue($i, $j) ;
                $m1->setValue($i, $j, 8) ;
                $this->assertFalse($m1->equals($m2)) ;
                $this->assertFalse($m2->equals($m1)) ;
                $m1->setValue($i, $j, $v) ;
            }
        }
        
        $m3 = Matrix::create(array(
            array(1, 2),
            array(3, 4),
            array(5, 6)
        ), 3, 3) ;
        $this->assertFalse($m1->equals($m3)) ;
        $this->assertFalse($m3->equals($m1)) ;
        
        
        $m4 = Matrix::create(array(
            array(1, 2),
            array(3, 4),
            array(5, 6)
        ), 4, 2) ;
        $this->assertFalse($m1->equals($m4)) ;
        $this->assertFalse($m4->equals($m1)) ;
    }

    public function test_clone() {
        $m1 = Matrix::create(array(
            array(1, 2),
            array(3, 4),
            array(5, 6)
        )) ;
        
        $m2 = clone($m1) ;
        $this->assertTrue($m1->equals($m2)) ;
    }
    
    public function test_add() {
        
        $m1 = Matrix::create(array(
            array(1, 2),
            array(3, 4),
        )) ;

        $m12 = Matrix::create(array(
            array(1, 2),
            array(3, 4),
        )) ;
        
        $m2 = Matrix::create(array(
            array(5, 6),
            array(7, 8),
        )) ;
        
        $res = Matrix::create(array(
            array(6, 8),
            array(10, 12),
        )) ;
        
        $m3 = Matrix::sum($m1, $m2) ;
        $this->assertTrue($m3->equals($res)) ;
        
        $m1->add($m2) ;
        $this->assertTrue($m1->equals($res)) ;
        
        $m1->substract($m2) ;
        $this->assertTrue($m1->equals($m12)) ;
        
    }
    
    public function test_multiply() {
        $m = Matrix::create(array(
            array(1, 2),
            array(3, 4),
        )) ;

        $mbis = clone $m ;
        
        $mtwice = Matrix::create(array(
            array(2, 4),
            array(6, 8),
        )) ;
        
        $m->multiply(2.0) ;
        $this->assertTrue($m->equals($mtwice)) ;
        
        $m->multiply(0.5) ;
        $this->assertTrue($m->equals($mbis)) ;
        
        $m2 = Matrix::multiplyStatic($m, 2.0) ;
        $this->assertTrue($m2->equals($mtwice)) ;
    }
    
    // MAtrix from http://fr.wikipedia.org/wiki/Produit_matriciel
    public function test_product() {
        $m1 = Matrix::create(array(
            array(1, 2, 0),
            array(4, 3, -1),
        )) ;

        $m2 = Matrix::create(array(
            array(5, 1),
            array(2, 3),
            array(3, 4)
        )) ;
        
        $res12 = Matrix::create(array(
            array(9, 7),
            array(23, 9),
        )) ;
        
        $res21 = Matrix::create(array(
            array(9, 13, -1),
            array(14, 13, -3),
            array(19, 18, -4)
        )) ;
        
        $m12 = Matrix::product($m1, $m2) ;
        $m21 = Matrix::product($m2, $m1) ;
        
        $this->assertTrue($m12->equals($res12)) ;
        $this->assertTrue($m21->equals($res21)) ;
    }
    
    public function test_zero() {
        $res23 = Matrix::create(array(
            array(0, 0, 0),
            array(0, 0, 0),
        )) ;
        
        $zero = Matrix::Zero(2, 3) ;
        $this->assertTrue($res23->equals($zero)) ;
    }
    
    public function test_I() {
        $res2 = Matrix::create(array(
            array(1, 0),
            array(0, 1),
        )) ;
        
        $i = Matrix::I(2) ;
        $this->assertTrue($res2->equals($i)) ;
    }
    
    public function test_transpose() {
        
        $m1 = Matrix::create(array(
            array(1, 2, 0),
            array(4, 3, -1),
        )) ;

        $m2 = Matrix::create(array(
            array (1, 4),
            array (2, 3),
            array (0, -1)
        )) ;
        
        $m3 = Matrix::transpose($m1) ;
        $m4 = Matrix::transpose($m2) ;
        
        $this->assertTrue($m3->equals($m2)) ;
        $this->assertTrue($m4->equals($m1)) ;
        
    }
    
}

