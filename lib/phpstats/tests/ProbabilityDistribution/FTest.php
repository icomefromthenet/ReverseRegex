<?php
use PHPStats\PDistribution\F as F,
    PHPStats\PCalculator\F as FCalculator,
    PHPStats\Tests\Base\PDTest;


class FTest extends PDTest
{
    /**
      *  @var  PHPStats\PCalculator\F
      */
    private $testObject;

    public function __construct()
    {
	parent::__construct();
	$fCalculator = new FCalculator($this->randomGenerator,$this->basicStats,$this->chiSquare);
	$this->testObject = new F(12, 10,$fCalculator);
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
	$this->assertEquals(0.64389, round($this->testObject->pdf(1), 5));
	$this->assertEquals(0.29981, round($this->testObject->pdf(1.6), 5));
    }

    public function testCdf()
    {
	$this->assertEquals(0.5, round($this->testObject->cdf(1.01157), 5));
	$this->assertEquals(0.9, round($this->testObject->cdf(2.28405), 5));
    }

    public function testSF()
    {
	$this->assertEquals(0.5, round($this->testObject->sf(1.01157), 5));
	$this->assertEquals(0.1, round($this->testObject->sf(2.28405), 5));
    }

    public function testPpf()
    {
	$this->assertEquals(1.01157, round($this->testObject->ppf(0.5), 5));
	$this->assertEquals(2.28405, round($this->testObject->ppf(0.9), 5));
    }

    public function testIsf()
    {
	$this->assertEquals(1.01157, round($this->testObject->isf(0.5), 5));
	$this->assertEquals(2.28405, round($this->testObject->isf(0.1), 5));
    }

    public function testStats()
    {
	 $summaryStats = $this->testObject->stats('mvsk');

	$this->assertEquals(1.25, round($summaryStats['mean'], 5));
	$this->assertEquals(0.86806, round($summaryStats['variance'], 5));
	$this->assertEquals(3.57771, round($summaryStats['skew'], 5));
	$this->assertEquals(44.4, round($summaryStats['kurtosis'], 5));
    }
}
/* End of File */
