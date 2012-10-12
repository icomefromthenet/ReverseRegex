<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\LogNormal as LogNormal,
    PHPStats\PCalculator\LogNormal as LogNormalCalculator,
    PHPStats\Tests\Base\PDTest;


class LogNormalTest extends PDTest
{
    /**
      *  @var  PHPStats\PDistribution\LogNormal
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	$logNormalCalculator = new LogNormalCalculator($this->randomGenerator,$this->basicStats);
	$this->testObject = new LogNormal(3, 2.25,$logNormalCalculator);
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
	$this->assertEquals(0.04076, round($this->testObject->pdf(2), 5));
	$this->assertEquals(0.02968, round($this->testObject->pdf(7), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.1, round($this->testObject->cdf(2.93783), 5));
	$this->assertEquals(0.25, round($this->testObject->cdf(7.30286), 5));
	$this->assertEquals(0.5, round($this->testObject->cdf(20.0855), 5));
	$this->assertEquals(0.75, round($this->testObject->cdf(55.2426), 4));
	$this->assertEquals(0.9, round($this->testObject->cdf(137.322), 3));
    }

    public function testSF()
    {
	$this->assertEquals(0.9, round($this->testObject->sf(2.93783), 5));
	$this->assertEquals(0.75, round($this->testObject->sf(7.30286), 5));
	$this->assertEquals(0.5, round($this->testObject->sf(20.0855), 5));
	$this->assertEquals(0.25, round($this->testObject->sf(55.2426), 4));
	$this->assertEquals(0.1, round($this->testObject->sf(137.322), 3));
    }

    public function testPpf()
    {
	$this->assertEquals(2.97111, round($this->testObject->ppf(0.1), 5));
	$this->assertEquals(7.30296, round($this->testObject->ppf(0.25), 5));
	$this->assertEquals(20.08554, round($this->testObject->ppf(0.5), 5));
	$this->assertEquals(55.24183, round($this->testObject->ppf(0.75), 5));
	$this->assertEquals(135.78371, round($this->testObject->ppf(0.9), 5));
    }

    public function testIsf()
    {
	$this->assertEquals(2.97111, round($this->testObject->isf(0.9), 5));
	$this->assertEquals(7.30296, round($this->testObject->isf(0.75), 5));
	$this->assertEquals(20.08554, round($this->testObject->isf(0.5), 5));
	$this->assertEquals(55.24183, round($this->testObject->isf(0.25), 5));
	$this->assertEquals(135.78371, round($this->testObject->isf(0.1), 5));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(61.8678, round($summaryStats['mean'], 4));
	$this->assertEquals(32487.9, round($summaryStats['variance'], 1));
	$this->assertEquals(33.468, round($summaryStats['skew'], 3));
	$this->assertEquals(10075.3, round($summaryStats['kurtosis'], 1));
    }
}
/* End of File */