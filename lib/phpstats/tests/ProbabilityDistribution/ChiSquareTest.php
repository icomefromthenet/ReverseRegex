<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\ChiSquare as ChiSquare,
    PHPStats\PCalculator\ChiSquare as ChiSquareCalculator,
    PHPStats\Tests\Base\PDTest;

class ChiSquareTest extends PDTest
{
    private $testObject;
    
    public function __construct()
    {
	    parent::__construct();
	    
	    $this->testObject = new ChiSquare(5,$this->chiSquare);
    }
    
    public function testRvs()
    {
	$variates = array();
	for ($i = 0; $i < 10000; $i++) $variates[] = $this->testObject->rvs();
	$this->assertGreaterThanOrEqual(0.01, $this->statisticalTests->kolmogorovSmirnov($variates, $this->testObject));
	$this->assertLessThanOrEqual(0.99, $this->statisticalTests->kolmogorovSmirnov($variates, $this->testObject));
    }
    
    public function testPdf()
    {
	$this->assertEquals(0.14398, round($this->testObject->pdf(4), 5));
	$this->assertEquals(0.05511, round($this->testObject->pdf(8), 5));
    }
    
    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(4.35146), 4));
	$this->assertEquals(0.9, round($this->testObject->cdf(9.23636), 4));
    }
    
    public function testSF()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(4.35146), 5));
	$this->assertEquals(0.1, round($this->testObject->sf(9.23636), 4));
    }
    
    /*public function testPpf() {
	    $this->assertEquals(4.35144, round($this->testObject->ppf(0.5), 5));
	    $this->assertEquals(9.23636, round($this->testObject->ppf(0.9), 5));
    }
    
    public function testIsf() {
	    $this->assertEquals(4.35144, round($this->testObject->isf(0.5), 5));
	    $this->assertEquals(1.61030, round($this->testObject->isf(0.9), 5));
    }*/
    
    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');
    
	$this->assertEquals(5, round($summaryStats['mean'], 5));
	$this->assertEquals(10, round($summaryStats['variance'], 5));
	$this->assertEquals(1.26491, round($summaryStats['skew'], 5));
	$this->assertEquals(2.4, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
