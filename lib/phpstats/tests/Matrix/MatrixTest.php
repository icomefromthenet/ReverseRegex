<?php
namespace PHPStats\Tests\Matrix;

use PHPUnit_Framework_TestCase,
    PHPStats\Matrix\Matrix;

class MatrixTest extends PHPUnit_Framework_TestCase
{
    private $matrixA;
    private $matrixB;
    private $matrixC;
    
    /**
      *  @var BasicStats 
      */
    protected $basic;
    
    
    public function __construct()
    {
	$this->matrixA = new Matrix('[1, 2, 3, 4; 5, 6, 7, 8; 9, 10, 11, 12; 13, 14, 15, 16]');
	$this->matrixB = new Matrix('[3, 6, 2, 7; 4, 1, 6, 3; 8, 5, 8, 3; 1, 7, 9, 0]');
	$this->matrixC = new Matrix(array(array(1, 2, 3), array(4, 5, 6), array(7, 8)));
    }

    public function testArrayConstruct()
    {
	
	$this->assertEquals(1, $this->matrixC->getElement(1, 1));
	$this->assertEquals(2, $this->matrixC->getElement(1, 2));
	$this->assertEquals(3, $this->matrixC->getElement(1, 3));
	$this->assertEquals(4, $this->matrixC->getElement(2, 1));
	$this->assertEquals(5, $this->matrixC->getElement(2, 2));
	$this->assertEquals(6, $this->matrixC->getElement(2, 3));
	$this->assertEquals(7, $this->matrixC->getElement(3, 1));
	$this->assertEquals(8, $this->matrixC->getElement(3, 2));
	$this->assertEquals(0, $this->matrixC->getElement(3, 3));
    }
    
    public function testGetElement()
    {
	$this->assertEquals(7, $this->matrixA->getElement(2, 3));
    }
    
    public function testSetElement()
    {
	$this->matrixA->setElement(2, 3, 99);
	
	$this->assertEquals(99, $this->matrixA->getElement(2, 3));
	
	$this->matrixA->setElement(2, 3, 7);
    }
    
    public function testGetRows()
    {
	$this->assertEquals(4, $this->matrixA->getRows());
    }
    
    public function testGetColumns()
    {
	$this->assertEquals(4, $this->matrixA->getColumns());
    }
}
/* End of File */