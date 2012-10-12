<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Cauchy as Cauchy,
PHPStats\PCalculator\Normal as NormalCalculator,
PHPStats\PCalculator\Cauchy as CauchyCalculator,
PHPStats\Tests\Base\PDTest;

class CauchyTest extends PDTest
{
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	
	$normalCalculator = new NormalCalculator($this->randomGenerator,$this->basicStats);
	$cauchyCalculator = new CauchyCalculator($this->randomGenerator,$this->basicStats,$normalCalculator);
	$this->testObject = new Cauchy(10, 5,$cauchyCalculator);
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
	    $this->assertEquals(0.01273, round($this->testObject->pdf(0), 5));
	    $this->assertEquals(0.02151, round($this->testObject->pdf(3), 5));
    }

    public function testCdf()
    {
	    $this->assertEquals(0.14758, round($this->testObject->cdf(0), 5));
	    $this->assertEquals(0.19743, round($this->testObject->cdf(3), 5));
    }

    public function testSf()
    {
	    $this->assertEquals(0.85242, round($this->testObject->sf(0), 5));
	    $this->assertEquals(0.80257, round($this->testObject->sf(3), 5));
    }

    public function testPpf()
    {
	    $this->assertEquals(0, round($this->testObject->ppf(0.14758), 3));
	    $this->assertEquals(3, round($this->testObject->ppf(0.19743), 3));
    }

    public function testIsf()
    {
	    $this->assertEquals(0, round($this->testObject->isf(0.85242), 3));
	    $this->assertEquals(3, round($this->testObject->isf(0.80257), 3));
    }

    public function testStats()
    {
	    $summaryStats = $this->testObject->stats('mvsk');

	    $this->assertTrue(is_nan($summaryStats['mean']));
	    $this->assertTrue(is_nan($summaryStats['variance']));
	    $this->assertTrue(is_nan($summaryStats['skew']));
	    $this->assertTrue(is_nan($summaryStats['kurtosis']));
    }
}
/* End of File */
