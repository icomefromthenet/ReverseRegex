<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Gamma as Gamma,
    PHPStats\PCalculator\Gamma as GammaCalculator,
    PHPStats\Tests\Base\PDTest;

class GammaTest extends PDTest
{
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	
	$cal = new GammaCalculator($this->randomGenerator,$this->basicStats);
	
	$this->testObject = new Gamma(10, 5,$cal);
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
	$this->assertEquals(0.02482, round($this->testObject->pdf(40), 5));
	$this->assertEquals(0.01747, round($this->testObject->pdf(60), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(48.3436), 4));
	$this->assertEquals(0.9, round($this->testObject->cdf(71.03), 2));
    }

    public function testSf()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(48.3436), 5));
	$this->assertEquals(0.1, round($this->testObject->sf(71.03), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(48.3436, round($this->testObject->ppf(0.5), 4));
	$this->assertEquals(71.03, round($this->testObject->ppf(0.9), 2));
    }

    public function testIsf()
    {
	$this->assertEquals(48.3436, round($this->testObject->isf(0.5), 5));
	$this->assertEquals(71.03, round($this->testObject->isf(0.1), 5));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(50, round($summaryStats['mean'], 5));
	$this->assertEquals(250, round($summaryStats['variance'], 5));
	$this->assertEquals(0.63246, round($summaryStats['skew'], 5));
	$this->assertEquals(0.6, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
