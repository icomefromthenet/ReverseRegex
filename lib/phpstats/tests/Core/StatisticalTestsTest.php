<?php
namespace PHPStats\Tests\Core;

use PHPStats\StatisticalTests as StatisticalTests,
    PHPStats\Generator\SrandRandom,
    PHPStats\BasicStats,
    PHPStats\PCalculator\ChiSquare as ChiSquareCalculator,
    PHPStats\PCalculator\StudentsT as StudentsTCalculator;

class StatisticalTestsTest extends \PHPUnit_Framework_TestCase
{
    private $datax;
    private $datay;
    private $dataz;
    
    private $statisticalTests;
    
    public function __construct()
    {
	$this->datax = array(1, 2, 3, 4, 5);
	$this->datay = array(10, 11, 12, 13, 14);
	$this->dataz = array(28.8, 27.1, 42.4, 53.5, 90);
	
	$generator = new SrandRandom();
	$basic     = new BasicStats();
	$chiSquare = new ChiSquareCalculator($generator,$basic);
	$studentsT = new StudentsTCalculator($generator,$basic,$chiSquare);
	
	$this->statisticalTests = new StatisticalTests($basic,$chiSquare,$studentsT);
    }
    
    public function test_oneSampleTTest()
    {
    }
    
    public function test_twoSampleTTest()
    {
    }
    
    public function test_pairedTTest()
    {
    }
    
    public function test_chiSquareTest()
    {
	$this->assertEquals(0.0003, round($this->statisticalTests->chiSquareTest(array(200, 150, 50, 250, 300, 50), array(180, 180, 40, 270, 270, 60), 2), 4));
    }
    
    public function test_kolmogorovSmirnov()
    {
    }
    
    public function test_kolmogorovCDF()
    {
	$this->assertEquals(0.73,    round($this->statisticalTests->kolmogorovCDF(1), 5));
	$this->assertEquals(0.99933, round($this->statisticalTests->kolmogorovCDF(2), 5));
	$this->assertEquals(0.03605, round($this->statisticalTests->kolmogorovCDF(0.5), 5));
    }
}
/* End of File */