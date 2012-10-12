<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\ContinuousUniform as ContinuousUniform,
    PHPStats\PCalculator\ContinuousUniform as ContinuousUniformCalculator,
    PHPStats\Tests\Base\PDTest;
    
class ContinuousUniformTest extends PDTest
{
    /**
      *  @var  PHPStats\PCalculator\ContinuousUniform
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	
	$continuousUniformCalculator = new ContinuousUniformCalculator($this->randomGenerator,$this->basicStats);
	$this->testObject = new ContinuousUniform(1, 10,$continuousUniformCalculator);
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
	$this->assertEquals(0.11111, round($this->testObject->pdf(4), 5));
	$this->assertEquals(0.11111, round($this->testObject->pdf(8), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.3333, round($this->testObject->cdf(4), 4));
	$this->assertEquals(0.8889, round($this->testObject->cdf(9), 4));
    }

    public function testSF()
    {
	$this->assertEquals(0.66667, round($this->testObject->sf(4), 5));
	$this->assertEquals(0.1111, round($this->testObject->sf(9), 4));
    }

    public function testPpf()
    {
	$this->assertEquals(4, round($this->testObject->ppf(0.33333), 4));
	$this->assertEquals(9, round($this->testObject->ppf(0.88889), 4));
    }

    public function testIsf()
    {
	$this->assertEquals(7, round($this->testObject->isf(0.33333), 4));
	$this->assertEquals(2, round($this->testObject->isf(0.88889), 4));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(5.5, round($summaryStats['mean'], 5));
	$this->assertEquals(6.75, round($summaryStats['variance'], 5));
	$this->assertEquals(0, round($summaryStats['skew'], 5));
	$this->assertEquals(-1.2, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
