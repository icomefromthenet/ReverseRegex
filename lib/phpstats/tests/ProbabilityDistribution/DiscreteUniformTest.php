<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\DiscreteUniform as DiscreteUniform,
    PHPStats\PCalculator\DiscreteUniform as DiscreteUniformCalculator,
    PHPStats\Tests\Base\PDTest;


class DiscreteUniformTest extends PDTest
{
    /**
      *  @var  PHPStats\PCalculator\DiscreteUniform
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	$discreteUniformCalculator = new DiscreteUniformCalculator($this->randomGenerator,$this->basicStats);
	$this->testObject = new DiscreteUniform(1, 10,$discreteUniformCalculator);
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
	    
	    for ($i = 1; $i < $variates; $i++) {
		    $variate = $this->testObject->rvs();
		    
		    if ($variate < $max_tested)
			    $observed[$variate]++;
		    else
			    $observed[$max_tested]++;
	    }
	    
	    for ($i = 1; $i < $max_tested; $i++) {
		    $expected[$i] = $variates * $this->testObject->pmf($i);
	    }
	    $expected[$max_tested] = $variates * $this->testObject->sf($max_tested - 1);
	    
	    $this->assertGreaterThanOrEqual(0.01, $this->statisticalTests->chiSquareTest($observed, $expected, $max_tested - 1));
	    $this->assertLessThanOrEqual(0.99, $this->statisticalTests->chiSquareTest($observed, $expected, $max_tested - 1));
    }

    public function testPmf()
    {
	    $this->assertEquals(0.1, $this->testObject->pmf(4));
    }

    public function testCdf()
    {
	    $this->assertEquals(0.4, $this->testObject->cdf(4));
    }

    public function testSf()
    {
	    $this->assertEquals(0.6, $this->testObject->sf(4));
    }

    public function testPpf()
    {
	    $this->assertEquals(2, $this->testObject->ppf(0.2));
    }

    public function testIsf()
    {
	    $this->assertEquals(9, $this->testObject->isf(0.2));
    }

    public function testStats()
    {
	    $summaryStats = $this->testObject->stats('mvsk');

	    $this->assertEquals(5.5, $summaryStats['mean']);
	    $this->assertEquals(8.33333, round($summaryStats['variance'], 5));
	    $this->assertEquals(0, $summaryStats['skew']);
	    $this->assertEquals(-1.22424, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
