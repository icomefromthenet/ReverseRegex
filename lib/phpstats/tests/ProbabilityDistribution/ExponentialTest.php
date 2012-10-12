<?php
use PHPStats\PDistribution\Exponential as Exponential,
    PHPStats\PCalculator\Exponential as ExponentialCalculator,
    PHPStats\Tests\Base\PDTest;


class ExponentialTest extends PDTest
{
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	
	$exponentialCalculator = new ExponentialCalculator($this->randomGenerator,$this->basicStats);
	
	$this->testObject = new Exponential(10,$exponentialCalculator);
    }

    public function testRvs()
    {
	$variates = array();
	for ($i = 0; $i < 10000; $i++) $variates[] = $this->testObject->rvs();
	$this->assertGreaterThanOrEqual(0.01, $this->statisticalTests->kolmogorovSmirnov($variates, $this->testObject));
	$this->assertLessThanOrEqual(0.99,    $this->statisticalTests->kolmogorovSmirnov($variates, $this->testObject));
    }

    public function testPdf()
    {
	$this->assertEquals(0.06738, round($this->testObject->pdf(0.5), 5));
	$this->assertEquals(0.82085, round($this->testObject->pdf(0.25), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(0.06931), 4));
	$this->assertEquals(0.9, round($this->testObject->cdf(0.23026), 5));
    }

    public function testSf()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(0.06931), 4));
	$this->assertEquals(0.1, round($this->testObject->sf(0.23026), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(0.06931, round($this->testObject->ppf(0.5), 5));
	$this->assertEquals(0.23026, round($this->testObject->ppf(0.9), 5));
    }

    public function testIsf()
    {
	$this->assertEquals(0.06931, round($this->testObject->isf(0.5), 5));
	$this->assertEquals(0.23026, round($this->testObject->isf(0.1), 5));
    }

    public function testStats() 
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(0.1, round($summaryStats['mean'], 5));
	$this->assertEquals(0.01, round($summaryStats['variance'], 5));
	$this->assertEquals(2, round($summaryStats['skew'], 5));
	$this->assertEquals(6, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */