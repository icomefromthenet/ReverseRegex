<?php
namespace PHPStats\Tests\RegressionModel;

use PHPStats\RegressionModel\LinearRegression as LinearRegression,
    PHPStats\BasicStats;	


class LinearRegressionTest extends \PHPUnit_Framework_TestCase
{
    private $regressionModel;

    public function __construct()
    {
	$basic = new BasicStats();
	$datax = array(3.385,	0.48,	1.35,	465,	36.33,	27.66,	14.83,	1.04,	4.19,	0.425,	0.101,	0.92,	1,	0.005,	0.06,	3.5,	2,	1.7,	2547,	0.023,	187.1,	521,	0.785,	10,	3.3,	0.2,	1.41,	529,	207,	85,	0.75,	62,	6654,	3.5,	6.8,	35,	4.05,	0.12,	0.023,	0.01,	1.4,	250,	2.5,	55.5,	100,	52.16,	10.55,	0.55,	60,	3.6,	4.288,	0.28,	0.075,	0.122,	0.048,	192,	3,	160,	0.9,	1.62,	0.104,	4.235);
        $datay = array(44.5,	15.5,	8.1,	423,	119.5,	115,	98.2,	5.5,	58,	6.4,	4,	5.7,	6.6,	0.14,	1,	10.8,	12.3,	6.3,	4603,	0.3,	419,	655,	3.5,	115,	25.6,	5,	17.5,	680,	406,	325,	12.3,	1320,	5712,	3.9,	179,	56,	17,	1,	0.4,	0.25,	12.5,	490,	12.1,	175,	157,	440,	179.5,	2.4,	81,	21,	39.2,	1.9,	1.2,	3,	0.33,	180,	25,	169,	2.6,	11.4,	2.5,	50.4);
        $this->regressionModel = new LinearRegression($datax, $datay,$basic);
    }
    
    public function testPredict()
    {
        $this->assertEquals(139.329214591, round($this->regressionModel->predict(50), 10));
    }
    
    public function testGetAlpha()
    {
        $this->assertEquals(91.0043962074, round($this->regressionModel->getAlpha(), 10));
    }
    
    public function testGetBeta()
    {
        $this->assertEquals(0.9664963677, round($this->regressionModel->getBeta(), 10));
    }
    
    public function testGetCorrelation()
    {
        $this->assertEquals(0.9341638423, round($this->regressionModel->getCorrelation(), 10));
    }
}
/* End of File */
