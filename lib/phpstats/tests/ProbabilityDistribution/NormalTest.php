<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Normal as Normal,
    PHPStats\PCalculator\Normal as NormalCalculator,
    PHPStats\Tests\Base\PDTest;
    
class NormalTest extends PDTest
{
	
    /**
      *  @var Normal 
      */
    private $testObject;
    

    public function __construct()
    {
	parent::__construct();
    
	$cal   = new NormalCalculator($this->randomGenerator,$this->basicStats);    
	
	$this->testObject = new Normal(10, 25,$cal);
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
	$this->assertEquals(0.02218, round($this->testObject->pdf(2), 5));
	$this->assertEquals(0.06664, round($this->testObject->pdf(7), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(10), 5));
	$this->assertEquals(0.9, round($this->testObject->cdf(16.4078), 5));
    }

    public function testSf()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(10), 5));
	$this->assertEquals(0.1, round($this->testObject->sf(16.4078), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(10, $this->testObject->ppf(0.5));
	$this->assertEquals(16.3702, round($this->testObject->ppf(0.9), 4));
    }

    public function testIsf()
    {
	$this->assertEquals(10, $this->testObject->isf(0.5));
	$this->assertEquals(3.62979, round($this->testObject->isf(0.9), 5));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(10, $summaryStats['mean']);
	$this->assertEquals(25, $summaryStats['variance']);
	$this->assertEquals(0, $summaryStats['skew']);
	$this->assertEquals(0, $summaryStats['kurtosis']);
    }
}
/* End of File */
