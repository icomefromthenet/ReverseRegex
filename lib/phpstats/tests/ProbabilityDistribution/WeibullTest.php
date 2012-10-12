<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Weibull as Weibull,
    PHPStats\PCalculator\Weibull as WeibullCalculator,
    PHPStats\PCalculator\Exponential as ExponentialCalculator,
    PHPStats\Tests\Base\PDTest;


class WeibullTest extends PDTest
{
    /**
      *  @var  PHPStats\PDistribution\Weibull
      */
    private $testObject;

    public function __construct()
    {
	
	parent::__construct();
	$exponentialCalculator = new ExponentialCalculator($this->randomGenerator,$this->basicStats);
	$weibullCalculator     = new WeibullCalculator($this->randomGenerator,$this->basicStats,$exponentialCalculator);
	$this->testObject      = new Weibull(5, 1,$weibullCalculator);
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
	$this->assertEquals(0.2, round($this->testObject->pdf(0), 5));
	$this->assertEquals(0.14523, round($this->testObject->pdf(1.6), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(3.46574), 5));
	$this->assertEquals(0.9, round($this->testObject->cdf(11.5129), 5));
    }

    public function testSF()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(3.46574), 5));
	$this->assertEquals(0.1, round($this->testObject->sf(11.5129), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(3.46574, round($this->testObject->ppf(0.5), 5));
	$this->assertEquals(11.5129, round($this->testObject->ppf(0.9), 4));
    }

    public function testIsf()
    {
	$this->assertEquals(3.46574, round($this->testObject->isf(0.5), 5));
	$this->assertEquals(11.5129, round($this->testObject->isf(0.1), 4));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(5, round($summaryStats['mean'], 3));
	$this->assertEquals(25, round($summaryStats['variance'], 2));
	$this->assertEquals(2, round($summaryStats['skew'], 3));
	$this->assertEquals(6, round($summaryStats['kurtosis'], 3));
    }
}
/* End of File */
