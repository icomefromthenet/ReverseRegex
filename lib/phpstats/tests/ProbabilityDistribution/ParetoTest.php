<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Pareto as Pareto,
    PHPStats\PCalculator\Pareto as ParetoCalculator,
    PHPStats\Tests\Base\PDTest;


class ParetoTest extends PDTest
{
    /**
      *  @var PHPStats\PDistribution\Pareto 
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	$paretoCalculator = new ParetoCalculator($this->randomGenerator,$this->basicStats);
	$this->testObject = new Pareto(1, 5,$paretoCalculator);
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
	$this->assertEquals(1.67449, round($this->testObject->pdf(1.2), 5));
	$this->assertEquals(0.29802, round($this->testObject->pdf(1.6), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(1.1487), 3));
	$this->assertEquals(0.9, round($this->testObject->cdf(1.58489), 3));
    }

    public function testSF()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(1.1487), 3));
	$this->assertEquals(0.1, round($this->testObject->sf(1.58489), 3));
    }

    public function testPpf()
    {
	$this->assertEquals(1.1487, round($this->testObject->ppf(0.5), 4));
	$this->assertEquals(1.58489, round($this->testObject->ppf(0.9), 5));
    }

    public function testIsf()
    {
	$this->assertEquals(1.1487, round($this->testObject->isf(0.5), 4));
	$this->assertEquals(1.58489, round($this->testObject->isf(0.1), 5));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(1.25, round($summaryStats['mean'], 2));
	$this->assertEquals(0.10417, round($summaryStats['variance'], 5));
	$this->assertEquals(4.64758, round($summaryStats['skew'], 5));
	$this->assertEquals(70.8, round($summaryStats['kurtosis'], 1));
    }
}
/* End of File */
