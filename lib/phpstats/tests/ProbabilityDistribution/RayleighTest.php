<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Rayleigh as Rayleigh,
    PHPStats\PCalculator\Rayleigh as RayleighCalculator,
    PHPStats\PCalculator\Weibull as WeibullCalculator,
    PHPStats\PCalculator\Exponential as ExponentialCalculator,
    PHPStats\Tests\Base\PDTest;


class RayleighTest extends PDTest
{
    /**
      *  @var  PHPStats\PDistribution\Rayleigh
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	
	$expCal = new ExponentialCalculator($this->randomGenerator,$this->basicStats);
	$weiCal = new WeibullCalculator($this->randomGenerator,$this->basicStats,$expCal);
	$rayleighCalculator = new RayleighCalculator($this->randomGenerator,$this->basicStats,$weiCal);
	$this->testObject = new Rayleigh(2,$rayleighCalculator);
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
	$this->assertEquals(0.22062, round($this->testObject->pdf(1), 5));
	$this->assertEquals(0.12115, round($this->testObject->pdf(0.5), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.25, round($this->testObject->cdf(1.51706), 5));
	$this->assertEquals(0.5, round($this->testObject->cdf(2.35482), 5));
    }

    public function testSF()
    {
	$this->assertEquals(0.75, round($this->testObject->sf(1.51706), 5));
	$this->assertEquals(0.5, round($this->testObject->sf(2.35482), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(1.51706, round($this->testObject->ppf(0.25), 5));
	$this->assertEquals(2.35482, round($this->testObject->ppf(0.5), 5));
    }

    public function testIsf()
    {
	$this->assertEquals(1.51706, round($this->testObject->isf(0.75), 5));
	$this->assertEquals(2.35482, round($this->testObject->isf(0.5), 5));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(2.50663, round($summaryStats['mean'], 5));
	$this->assertEquals(1.71681, round($summaryStats['variance'], 5));
	$this->assertEquals(0.63111, round($summaryStats['skew'], 5));
	$this->assertEquals(0.24509, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
