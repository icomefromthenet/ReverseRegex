<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\Beta as Beta,
PHPStats\PCalculator\Beta as BetaCalculator,
PHPStats\PCalculator\Gamma as GammaCalculator,
PHPStats\Tests\Base\PDTest;


class BetaTest extends PDTest
{

    /**
      *  @var PHPStats\PDistribution\Beta 
      */
    private $testObject;
    
    public function __construct()
    {
	parent::__construct();
	
	$gamma = new GammaCalculator($this->randomGenerator,$this->basicStats);
	$beta_calculator  = new BetaCalculator($gamma,$this->randomGenerator,$this->basicStats);
	$this->testObject = new Beta(10, 5,$beta_calculator);
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
	$this->assertEquals(0.00210, round($this->testObject->pdf(0.2), 5));
	$this->assertEquals(3.27191, round($this->testObject->pdf(0.7), 5));
    }
    
    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(0.674249), 5));
	$this->assertEquals(0.9, round($this->testObject->cdf(0.814866), 5));
    }
    
    public function testSf()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(0.674249), 5));
	$this->assertEquals(0.1, round($this->testObject->sf(0.814866), 5));
    }
    
    public function testPpf()
    {
	$this->assertEquals(0.674249, round($this->testObject->ppf(0.5), 6));
	$this->assertEquals(0.814866, round($this->testObject->ppf(0.9), 6));
    }
    
    public function testIsf()
    {
	$this->assertEquals(0.674249, round($this->testObject->isf(0.5), 6));
	$this->assertEquals(0.508035, round($this->testObject->isf(0.9), 6));
    }
    
    public function testStats()
    {
	$summaryStats = $this->testObject->stats('mvsk');
	$this->assertEquals(0.66667, round($summaryStats['mean'], 5));
	$this->assertEquals(0.01389, round($summaryStats['variance'], 5));
	$this->assertEquals(-0.33276, round($summaryStats['skew'], 5));
	$this->assertEquals(-0.17647, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
