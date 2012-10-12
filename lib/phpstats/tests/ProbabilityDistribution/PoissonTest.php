<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Poisson as Poisson,
    PHPStats\PCalculator\Poisson as PoissonCalculator,
    PHPStats\Tests\Base\PDTest;


class PoissonTest extends PDTest
{
    /**
      *  @var  PHPStats\PDistribution\Poisson
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	$poissonCalculator = new PoissonCalculator($this->randomGenerator,$this->basicStats);
	$this->testObject = new Poisson(5,$poissonCalculator);
    }

    public function testRvs()
    {
	$variates = 10000;
	$max_tested = 10;
	$expected = array();
	$observed = array();

	for ($i = 0; $i <= $max_tested; $i++) {
		$expected[] = 0;
		$observed[] = 0;
	}
	
	for ($i = 0; $i < $variates; $i++) {
		$variate = $this->testObject->rvs();
		
		if ($variate < $max_tested)
			$observed[$variate]++;
		else
			$observed[$max_tested]++;
	}
	
	for ($i = 0; $i < $max_tested; $i++) {
		$expected[$i] = $variates * $this->testObject->pmf($i);
	}
	$expected[$max_tested] = $variates * $this->testObject->sf($max_tested - 1);
	
	$this->assertGreaterThanOrEqual(0.01, $this->statisticalTests->chiSquareTest($observed, $expected, $max_tested - 1));
	$this->assertLessThanOrEqual(0.99, $this->statisticalTests->chiSquareTest($observed, $expected, $max_tested - 1));
    }

    public function test_pmf()
    {
	$this->assertEquals(0.17547, round($this->testObject->pmf(5), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.61596, round($this->testObject->cdf(5), 5));
    }

    public function testSF()
    {
	$this->assertEquals(0.38404, round($this->testObject->sf(5), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(5, $this->testObject->ppf(0.5));
    }

    public function testIsf()
    {
	$this->assertEquals(5, $this->testObject->isf(0.5));
    }

    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(5, $summaryStats['mean']);
	$this->assertEquals(5, $summaryStats['variance']);
	$this->assertEquals(0.44721, round($summaryStats['skew'], 5));
	$this->assertEquals(0.2, $summaryStats['kurtosis']);
    }
}
/* End of File */
