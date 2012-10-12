<?php
namespace PHPStats\Tests\Matrix;

use PHPUnit_Framework_TestCase,
    PHPStats\Matrix\Matrix,
    PHPStats\Matrix\MatrixBuilder,
    PHPStats\Matrix\MatrixUtil,
    PHPStats\Matrix\MatrixMath,
    PHPStats\BasicStats;

//{{1,2,3,4},{5,6,7,8},{9,10,11,12},{13,14,15,16}}*{{3,6,2,7},{4,1,6,3},{8,5,8,3},{1,7,9,0}}


class MatrixMathTest extends PHPUnit_Framework_TestCase
{
    private $matrixA;
    private $matrixB;
    private $matrixC;
    
    /**
      *  @var MatrixMath 
      */
    protected $math;
    
    
    public function __construct()
    {
	$util          = new MatrixUtil();
	$builder       = new MatrixBuilder();
	$basic   = new BasicStats();
	
	
	$this->math    = new MatrixMath($builder,$util,$basic);
    }
    
    public function setUp()
    {
	$this->matrixA = new Matrix('[1, 2, 3, 4; 5, 6, 7, 8; 9, 10, 11, 12; 13, 14, 15, 16]');
	$this->matrixB = new Matrix('[3, 6, 2, 7; 4, 1, 6, 3; 8, 5, 8, 3; 1, 7, 9, 0]');
	$this->matrixC = new Matrix(array(array(1, 2, 3), array(4, 5, 6), array(7, 8)));
    }
    

    public function testAdd()
    {
	$sum = $this->math->add($this->matrixA,$this->matrixB);
	
	$this->assertEquals(4, $sum->getElement(1, 1));
	$this->assertEquals(8, $sum->getElement(1, 2));
	$this->assertEquals(5, $sum->getElement(1, 3));
	$this->assertEquals(11, $sum->getElement(1, 4));
	$this->assertEquals(9, $sum->getElement(2, 1));
	$this->assertEquals(7, $sum->getElement(2, 2));
	$this->assertEquals(13, $sum->getElement(2, 3));
	$this->assertEquals(11, $sum->getElement(2, 4));
	$this->assertEquals(17, $sum->getElement(3, 1));
	$this->assertEquals(15, $sum->getElement(3, 2));
	$this->assertEquals(19, $sum->getElement(3, 3));
	$this->assertEquals(15, $sum->getElement(3, 4));
	$this->assertEquals(14, $sum->getElement(4, 1));
	$this->assertEquals(21, $sum->getElement(4, 2));
	$this->assertEquals(24, $sum->getElement(4, 3));
	$this->assertEquals(16, $sum->getElement(4, 4));
    }
    
    public function testReduce()
    {
	$reduction = $this->math->reduce($this->matrixA,2, 3);
	
	$this->assertEquals(1, $reduction->getElement(1, 1));
	$this->assertEquals(2, $reduction->getElement(1, 2));
	$this->assertEquals(4, $reduction->getElement(1, 3));
	$this->assertEquals(9, $reduction->getElement(2, 1));
	$this->assertEquals(10, $reduction->getElement(2, 2));
	$this->assertEquals(12, $reduction->getElement(2, 3));
	$this->assertEquals(13, $reduction->getElement(3, 1));
	$this->assertEquals(14, $reduction->getElement(3, 2));
	$this->assertEquals(16, $reduction->getElement(3, 3));
    }
    
    public function testSubstract()
    {
	$difference = $this->math->subtract($this->matrixA,$this->matrixB);
	
	$this->assertEquals(-2, $difference->getElement(1, 1));
	$this->assertEquals(-4, $difference->getElement(1, 2));
	$this->assertEquals(1, $difference->getElement(1, 3));
	$this->assertEquals(-3, $difference->getElement(1, 4));
	$this->assertEquals(1, $difference->getElement(2, 1));
	$this->assertEquals(5, $difference->getElement(2, 2));
	$this->assertEquals(1, $difference->getElement(2, 3));
	$this->assertEquals(5, $difference->getElement(2, 4));
	$this->assertEquals(1, $difference->getElement(3, 1));
	$this->assertEquals(5, $difference->getElement(3, 2));
	$this->assertEquals(3, $difference->getElement(3, 3));
	$this->assertEquals(9, $difference->getElement(3, 4));
	$this->assertEquals(12, $difference->getElement(4, 1));
	$this->assertEquals(7, $difference->getElement(4, 2));
	$this->assertEquals(6, $difference->getElement(4, 3));
	$this->assertEquals(16, $difference->getElement(4, 4));
    }
    
    public function testScalarMultiply()
    {
	$product = $this->math->scalarMultiply($this->matrixA,2);
	
	$this->assertEquals(2, $product->getElement(1, 1));
	$this->assertEquals(4, $product->getElement(1, 2));
	$this->assertEquals(6, $product->getElement(1, 3));
	$this->assertEquals(8, $product->getElement(1, 4));
	$this->assertEquals(10, $product->getElement(2, 1));
	$this->assertEquals(12, $product->getElement(2, 2));
	$this->assertEquals(14, $product->getElement(2, 3));
	$this->assertEquals(16, $product->getElement(2, 4));
	$this->assertEquals(18, $product->getElement(3, 1));
	$this->assertEquals(20, $product->getElement(3, 2));
	$this->assertEquals(22, $product->getElement(3, 3));
	$this->assertEquals(24, $product->getElement(3, 4));
	$this->assertEquals(26, $product->getElement(4, 1));
	$this->assertEquals(28, $product->getElement(4, 2));
	$this->assertEquals(30, $product->getElement(4, 3));
	$this->assertEquals(32, $product->getElement(4, 4));
    }
    
    public function testDotMultiply()
    {
	$product = $this->math->dotMultiply($this->matrixA,$this->matrixB);

	$this->assertEquals(39, $product->getElement(1, 1));
	$this->assertEquals(51, $product->getElement(1, 2));
	$this->assertEquals(74, $product->getElement(1, 3));
	$this->assertEquals(22, $product->getElement(1, 4));
	$this->assertEquals(103, $product->getElement(2, 1));
	$this->assertEquals(127, $product->getElement(2, 2));
	$this->assertEquals(174, $product->getElement(2, 3));
	$this->assertEquals(74, $product->getElement(2, 4));
	$this->assertEquals(167, $product->getElement(3, 1));
	$this->assertEquals(203, $product->getElement(3, 2));
	$this->assertEquals(274, $product->getElement(3, 3));
	$this->assertEquals(126, $product->getElement(3, 4));
	$this->assertEquals(231, $product->getElement(4, 1));
	$this->assertEquals(279, $product->getElement(4, 2));
	$this->assertEquals(374, $product->getElement(4, 3));
	$this->assertEquals(178, $product->getElement(4, 4));
    }
    
    public function testDeterminant()
    {
	$this->assertEquals(0, $this->math->determinant($this->matrixA));
	$this->assertEquals(-1656, $this->math->determinant($this->matrixB));
    }
    
    
    
    public function testInverse()
    {
	$inverse = $this->math->inverse($this->matrixB);
	$inverse = $this->math->scalarMultiply($inverse,1656);
	
	$this->assertEquals(-66, $inverse->getElement(1, 1));
	$this->assertEquals(-197, $inverse->getElement(1, 2));
	$this->assertEquals(351, $inverse->getElement(1, 3));
	$this->assertEquals(-166, $inverse->getElement(1, 4));
	$this->assertEquals(102, $inverse->getElement(2, 1));
	$this->assertEquals(-373, $inverse->getElement(2, 2));
	$this->assertEquals(135, $inverse->getElement(2, 3));
	$this->assertEquals(106, round($inverse->getElement(2, 4)));
	$this->assertEquals(-72, $inverse->getElement(3, 1));
	$this->assertEquals(312, $inverse->getElement(3, 2));
	$this->assertEquals(-144, $inverse->getElement(3, 3));
	$this->assertEquals(120, $inverse->getElement(3, 4));
	$this->assertEquals(198, round($inverse->getElement(4, 1)));
	$this->assertEquals(315, $inverse->getElement(4, 2));
	$this->assertEquals(-225, $inverse->getElement(4, 3));
	$this->assertEquals(-54, $inverse->getElement(4, 4));
    }
    
    
    public function testPow()
    {
	$power = $this->math->pow($this->matrixB, 3);
	
	$this->assertEquals(1513, $power->getElement(1, 1));
	$this->assertEquals(1339, $power->getElement(1, 2));
	$this->assertEquals(1983, $power->getElement(1, 3));
	$this->assertEquals(1004, $power->getElement(1, 4));
	$this->assertEquals(1266, $power->getElement(2, 1));
	$this->assertEquals(1266, $power->getElement(2, 2));
	$this->assertEquals(1743, $power->getElement(2, 3));
	$this->assertEquals(964,  $power->getElement(2, 4));
	$this->assertEquals(1980, $power->getElement(3, 1));
	$this->assertEquals(2130, $power->getElement(3, 2));
	$this->assertEquals(2857, $power->getElement(3, 3));
	$this->assertEquals(1530, $power->getElement(3, 4));
	$this->assertEquals(1524, $power->getElement(4, 1));
	$this->assertEquals(1641, $power->getElement(4, 2));
	$this->assertEquals(1977, $power->getElement(4, 3));
	$this->assertEquals(1243, $power->getElement(4, 4));
    }
    
    
    public function testTrace()
    {
	$this->assertEquals(12, $this->math->trace($this->matrixB));
    }
        
}
/* End of File */