<?php
namespace PHPStats\Tests\ProbabilityDistribution;

use PHPStats\PDistribution\StudentsT as StudentsT,
    PHPStats\PCalculator\StudentsT as StudentsTCalculator,
    PHPStats\Tests\Base\PDTest;


class StudentsTTest extends PDTest
{
    /**
      *  @var  PHPStats\PDistribution\StudentsT
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	$studentsTCalculator = new StudentsTCalculator($this->randomGenerator,$this->basicStats,$this->chiSquare);
	
	$this->testObject = new StudentsT(5,$studentsTCalculator);
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
	$this->assertEquals(0.37961, round($this->testObject->pdf(0), 5));
	$this->assertEquals(0.10982, round($this->testObject->pdf(1.6), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(0), 5));
	$this->assertEquals(0.9, round($this->testObject->cdf(1.47588), 5));
    }

    public function testSF()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(0), 5));
	$this->assertEquals(0.1, round($this->testObject->sf(1.47588), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(0, round($this->testObject->ppf(0.5), 4));
	$this->assertEquals(1.47588, round($this->testObject->ppf(0.9), 5));
    }

    public function testIsf()
    {
	$this->assertEquals(0, round($this->testObject->isf(0.5), 4));
	$this->assertEquals(1.47588, round($this->testObject->isf(0.1), 5));
    }

    public function testStats() 
    {	
	$summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(0, round($summaryStats['mean'], 5));
	$this->assertEquals(1.66667, round($summaryStats['variance'], 5));
	$this->assertEquals(0, round($summaryStats['skew'], 5));
	$this->assertEquals(6, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
