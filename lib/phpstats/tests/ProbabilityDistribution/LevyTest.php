<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Levy as Levy,
    PHPStats\PCalculator\Levy as LevyCalculator,
    PHPStats\Tests\Base\PDTest;

class LevyTest extends PDTest
{
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	$levyCalculator = new LevyCalculator($this->randomGenerator,$this->basicStats);
	$this->testObject = new Levy(0, 1,$levyCalculator);
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
	$this->assertEquals(0.24197, round($this->testObject->pdf(1), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.31731, round($this->testObject->cdf(1), 5));
    }

    public function testSF()
    {
	$this->assertEquals(0.68269, round($this->testObject->sf(1), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(1, round($this->testObject->ppf(0.31731), 2));
    }

    public function testIsf()
    {
	$this->assertEquals(1, round($this->testObject->isf(0.68269), 2));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(INF, $summaryStats['mean']);
	$this->assertEquals(INF, $summaryStats['variance']);
	$this->assertEquals(NAN, $summaryStats['skew']);
	$this->assertEquals(NAN, $summaryStats['kurtosis']);
    }
}
/* End of File */
