<?php
namespace PHPStats\Tests\RegressionModel;

use PHPStats\RegressionModel\LogarithmicRegression as LogarithmicRegression,
    PHPStats\BasicStats;

class LogarithmicRegressionTest extends \PHPUnit_Framework_TestCase
{
    private $regressionModel;

    public function __construct()
    {
	$basic = new BasicStats();
	$datax = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
	$datay = array(6, 9.5, 13, 15, 16.5, 17.5, 18.5, 19, 19.5, 19.7, 19.8);
        $this->regressionModel = new LogarithmicRegression($datax, $datay,$basic);
    }
    
    public function testPredict()
    {
        $this->assertEquals(24.39781322, round($this->regressionModel->predict(20), 8));
    }
    
    public function testGetAlpha()
    {
        $this->assertEquals(6.09934114, round($this->regressionModel->getAlpha(), 8));
    }
    
    public function testGetBeta()
    {
        $this->assertEquals(6.108180041, round($this->regressionModel->getBeta(), 9));
    }
    
    public function testGetCorrelation()
    {
	$this->assertEquals(0.9931293099, round($this->regressionModel->getCorrelation(), 10));
    }
}
/* End of File */
