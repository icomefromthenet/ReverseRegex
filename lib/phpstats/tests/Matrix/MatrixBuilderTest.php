<?php
namespace PHPStats\Tests\Matrix;

use PHPUnit_Framework_TestCase,
    PHPStats\Matrix\Matrix,
    PHPStats\Matrix\MatrixBuilder,
    PHPStats\Matrix\MatrixUtil,
    PHPStats\BasicStats;

//{{1,2,3,4},{5,6,7,8},{9,10,11,12},{13,14,15,16}}*{{3,6,2,7},{4,1,6,3},{8,5,8,3},{1,7,9,0}}


class MatrixBuilderTest extends PHPUnit_Framework_TestCase
{
    private $matrixA;
    private $matrixB;
    private $matrixC;
    
    /**
      *  @var MatrixBuilder 
      */
    protected $builder;
    
    public function __construct()
    {
	$util          = new MatrixUtil();
	$builder       = new MatrixBuilder();  
	
	$this->matrixA = new Matrix('[1, 2, 3, 4; 5, 6, 7, 8; 9, 10, 11, 12; 13, 14, 15, 16]');
	$this->matrixB = new Matrix('[3, 6, 2, 7; 4, 1, 6, 3; 8, 5, 8, 3; 1, 7, 9, 0]');
	$this->matrixC = new Matrix(array(array(1, 2, 3), array(4, 5, 6), array(7, 8)));
	$this->builder = $builder;
    }

    public function testTranspose()
    {
	$transpose = $this->builder->transpose($this->matrixA);
	
	$this->assertEquals(1, $transpose->getElement(1, 1));
	$this->assertEquals(5, $transpose->getElement(1, 2));
	$this->assertEquals(9, $transpose->getElement(1, 3));
	$this->assertEquals(13, $transpose->getElement(1, 4));
	$this->assertEquals(2, $transpose->getElement(2, 1));
	$this->assertEquals(6, $transpose->getElement(2, 2));
	$this->assertEquals(10, $transpose->getElement(2, 3));
	$this->assertEquals(14, $transpose->getElement(2, 4));
	$this->assertEquals(3, $transpose->getElement(3, 1));
	$this->assertEquals(7, $transpose->getElement(3, 2));
	$this->assertEquals(11, $transpose->getElement(3, 3));
	$this->assertEquals(15, $transpose->getElement(3, 4));
	$this->assertEquals(4, $transpose->getElement(4, 1));
	$this->assertEquals(8, $transpose->getElement(4, 2));
	$this->assertEquals(12, $transpose->getElement(4, 3));
	$this->assertEquals(16, $transpose->getElement(4, 4));
    }
    
    
    public function testNumericConstructor()
    {
	    //Instantiate a matrix using the numeric constructor and then multiply it against our test one, to test identity multiplication
	    $identity = $this->builder->identity(4);
	    
	    //$sample_matrix = new Matrix()
	    /*
	    for ($i = 1; $i <= $multiplied->getRows(); $i++) {
		    for ($j = 1; $j <= $multiplied->getColumns(); $j++) {
			    $this->assertEquals($this->matrixB->getElement($i, $j), $multiplied->getElement($i, $j));
		    }
	    } */
    }
    
        
        
}

/* End of File */