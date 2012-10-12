<?php
namespace PHPStats\Tests\RegressionModel;

use PHPStats\RegressionModel\ExponentialRegression as ExponentialRegression,
    PHPStats\BasicStats;	

class ExponentialRegressionTest extends \PHPUnit_Framework_TestCase
{
    private $regressionModel;

    public function __construct()
    {
	$basic = new BasicStats();
	
	$datax = array(0, 5, 8, 11, 15, 18, 22, 25, 30, 34, 38, 42, 45, 50);
	$datay = array(179.5, 168.7, 158.1, 149.2, 141.7, 134.6, 125.4, 123.3, 116.3, 113.2, 109.1, 105.7, 102.2, 100.5);
	$this->regressionModel = new ExponentialRegression($datax, $datay,$basic);
    }
    
    public function testPredict()
    {
	$this->assertEquals(84.34167895, round($this->regressionModel->predict(60), 8));
    }
    
    public function testGetAlpha()
    {
	$this->assertEquals(171.4429259, round($this->regressionModel->getAlpha(), 7));
    }
    
    public function testGetBeta()
    {
	$this->assertEquals(0.9882467115, round($this->regressionModel->getBeta(), 10));
    }
    
    public function testGetCorrelation()
    {
	$this->assertEquals(-0.9848429205, round($this->regressionModel->getCorrelation(), 10));
    }
}
/* End of File */
