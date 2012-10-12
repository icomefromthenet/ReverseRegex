<?php
namespace PHPStats\Tests\RegressionModel;

use PHPStats\RegressionModel\PowerRegression as PowerRegression,
    PHPStats\BasicStats;


class PowerRegressionTest extends\PHPUnit_Framework_TestCase
{
    private $regressionModel;

    public function __construct()
    {
	$basic = new BasicStats();
	$datax = array(17.6, 26, 31.9, 38.9, 45.8, 51.2, 58.1, 64.7, 66.7, 80.8, 82.9);
	$datay = array(159.9, 206.9, 236.8, 269.9, 300.6, 323.6, 351.7, 377.6, 384.1, 437.2, 444.7);

	$this->regressionModel = new PowerRegression($datax, $datay,$basic);
    }
    
    public function testPredict()
    {
	$this->assertEquals(305.7034150458, round($this->regressionModel->predict(47), 10));
    }
    
    public function testGetAlpha()
    {
	$this->assertEquals(24.12989312, round($this->regressionModel->getAlpha(), 8));
    }
    
    public function testGetBeta()
    {
	$this->assertEquals(0.65949782, round($this->regressionModel->getBeta(), 8));
    }
    
    public function testGetCorrelation()
    {
	$this->assertEquals(0.9999962538, round($this->regressionModel->getCorrelation(), 10));
    }
}
/* End of File */
