<?php
namespace PHPStats\Tests\Core;

/**
 * Note to any reading this file:
 * 
 * A number of these tests use rounded numbers.  That is, we are not testing
 * whether or not something is exactly right, but whether it is close enough.
 * In most cases, this is because certain functions, like the gamma function,
 * require numeric approximations to the real value.  Any efforts to sharpen
 * the corresponding approximations and therefore be able to make the tests
 * accurate to a higher degree would be most welcome.
 */

use PHPStats\BasicStats;

class StatsTest extends \PHPUnit_Framework_TestCase
{
	private $datax;
	private $datay;
	private $dataz;

	/**
	  *  @var BasicStats 
	  */
	protected $basic;


	public function __construct()
	{
		$this->datax = array(1, 2, 3, 4, 5);
		$this->datay = array(10, 11, 12, 13, 14);
		$this->dataz = array(28.8, 27.1, 42.4, 53.5, 90);
		$this->basic = new BasicStats();
	}

	public function testSum()
	{
		$this->assertEquals(15, $this->basic->sum($this->datax));
		$this->assertEquals(60, $this->basic->sum($this->datay));
		$this->assertEquals(241.8, $this->basic->sum($this->dataz));
	}

	public function testProduct()
	{
		$this->assertEquals(120, $this->basic->product($this->datax));
		$this->assertEquals(240240, $this->basic->product($this->datay));
		$this->assertEquals(159339674.88, $this->basic->product($this->dataz));
	}

	public function testAverage()
	{
		$this->assertEquals(3, $this->basic->average($this->datax));
		$this->assertEquals(12, $this->basic->average($this->datay));
		$this->assertEquals(48.36, $this->basic->average($this->dataz));
	}

	public function testGaverage()
	{
		$this->assertEquals(2.60517, round($this->basic->gaverage($this->datax), 5));
		$this->assertEquals(11.91596, round($this->basic->gaverage($this->datay), 5));
		$this->assertEquals(43.69832, round($this->basic->gaverage($this->dataz), 5));
	}

	public function testSumsquared()
	{
		$this->assertEquals(55, $this->basic->sumsquared($this->datax));
		$this->assertEquals(730, $this->basic->sumsquared($this->datay));
		$this->assertEquals(14323.86, $this->basic->sumsquared($this->dataz));
	}

	public function testSumXY()
	{
		$this->assertEquals(190, $this->basic->sumXY($this->datax, $this->datay));
		$this->assertEquals(3050.4, $this->basic->sumXY($this->dataz, $this->datay));
	}

	public function testSse()
	{
		$this->assertEquals(10, $this->basic->sse($this->datax));
		$this->assertEquals(10, $this->basic->sse($this->datay));
		$this->assertEquals(2630.412, $this->basic->sse($this->dataz));
	}

	public function testMse()
	{
		$this->assertEquals(2, $this->basic->mse($this->datax));
		$this->assertEquals(2, $this->basic->mse($this->datay));
		$this->assertEquals(526.0824, $this->basic->mse($this->dataz));
	}

	public function testCovariance()
	{
		$this->assertEquals(2, $this->basic->covariance($this->datax, $this->datay));
		$this->assertEquals(29.76, round($this->basic->covariance($this->dataz, $this->datay), 2));
	}

	public function testVariance()
	{
		$this->assertEquals(2, $this->basic->variance($this->datax));
		$this->assertEquals(2, $this->basic->variance($this->datay));
		$this->assertEquals(526.0824, round($this->basic->variance($this->dataz), 4));
	}

	public function testStddev()
	{
		$this->assertEquals(1.41421, round($this->basic->stddev($this->datax), 5));
		$this->assertEquals(1.41421, round($this->basic->stddev($this->datay), 5));
		$this->assertEquals(22.93649, round($this->basic->stddev($this->dataz), 5));
	}

	public function testSampleStddev()
	{
		$this->assertEquals(1.58114, round($this->basic->sampleStddev($this->datax), 5));
		$this->assertEquals(1.58114, round($this->basic->sampleStddev($this->datay), 5));
		$this->assertEquals(25.64377, round($this->basic->sampleStddev($this->dataz), 5));
	}

	public function testCorrelation()
	{
		$this->assertEquals(1.0, round($this->basic->correlation($this->datax, $this->datay), 5));
		$this->assertEquals(0.91747, round($this->basic->correlation($this->dataz, $this->datay), 5));
	}

	public function testFactorial()
	{
		$this->assertEquals(1, $this->basic->factorial(0));
		$this->assertEquals(1, $this->basic->factorial(1));
		$this->assertEquals(2, $this->basic->factorial(2));
		$this->assertEquals(120, $this->basic->factorial(5));
		$this->assertEquals(3628800, $this->basic->factorial(10));
	}

	public function testErf()
	{
		$this->assertEquals(-1, round($this->basic->erf(-25)));
		$this->assertEquals(0, round($this->basic->erf(0), 7));
		$this->assertEquals(0.5205, round($this->basic->erf(0.5), 7));
		$this->assertEquals(0.8427007, round($this->basic->erf(1), 7));
		$this->assertEquals(0.9661053, round($this->basic->erf(1.5), 7));
		$this->assertEquals(0.9953221, round($this->basic->erf(2), 7));
		$this->assertEquals(0.9999993, round($this->basic->erf(3.5), 7));
	}

	public function testIerf()
	{
		$this->assertEquals(0.4769296, round($this->basic->ierf(0.5), 7));
		$this->assertEquals(0.2724627, round($this->basic->ierf(0.3), 7));
		$this->assertEquals(0.7321501, round($this->basic->ierf(0.7), 7));
	}

	public function testGamma()
	{
		$this->assertEquals(1, round($this->basic->gamma(1), 3));
		$this->assertEquals(1, round($this->basic->gamma(2), 3));
		$this->assertEquals(1.3293403881791, round($this->basic->gamma(2.5), 13));
		$this->assertEquals(2, round($this->basic->gamma(3), 5));
		$this->assertEquals(6, round($this->basic->gamma(4), 5));
		$this->assertEquals(24, round($this->basic->gamma(5), 5));
		$this->assertEquals(120, round($this->basic->gamma(6), 4));
	}

	public function testGammaln()
	{
		$this->assertEquals(round(log(1), 3), round($this->basic->gammaln(1), 3));
		$this->assertEquals(round(log(1), 3), round($this->basic->gammaln(2), 3));
		$this->assertEquals(round(log(1.3293326), 5), round($this->basic->gammaln(2.5), 5));
		$this->assertEquals(round(log(2), 5), round($this->basic->gammaln(3), 5));
		$this->assertEquals(round(log(6), 5), round($this->basic->gammaln(4), 5));
		$this->assertEquals(round(log(24), 5), round($this->basic->gammaln(5), 5));
		$this->assertEquals(round(log(120), 4), round($this->basic->gammaln(6), 4));
	}

	public function testIgamma()
	{
		$this->assertEquals(1, round($this->basic->igamma(1, false), 0));
		$this->assertEquals(2, round($this->basic->igamma(1), 1));
		$this->assertEquals(2.5, round($this->basic->igamma(1.3293326), 1));
		$this->assertEquals(3, round($this->basic->igamma(2), 1));
		$this->assertEquals(4, round($this->basic->igamma(6), 2));
		$this->assertEquals(5, round($this->basic->igamma(24), 1));
		$this->assertEquals(6, round($this->basic->igamma(120), 2));
	}

	public function testDigamma()
	{
		$this->assertEquals(-0.57722, round($this->basic->digamma(1), 5));
		$this->assertEquals(0.42278, round($this->basic->digamma(2), 5));
		$this->assertEquals(0.92278, round($this->basic->digamma(3), 5));
		$this->assertEquals(1.25612, round($this->basic->digamma(4), 5));
		$this->assertEquals(1.38887, round($this->basic->digamma(4.5), 5));
		$this->assertEquals(1.50612, round($this->basic->digamma(5), 5));
	}

	public function testLambert()
	{
		$this->assertEquals(0, round($this->basic->lambert(0), 6));
		$this->assertEquals(0.567143, round($this->basic->lambert(1), 6));
		$this->assertEquals(0.852606, round($this->basic->lambert(2), 6));
		$this->assertEquals(1.049909, round($this->basic->lambert(3), 6));
		$this->assertEquals(1.267238, round($this->basic->lambert(4.5), 6));
		$this->assertEquals(1.326725, round($this->basic->lambert(5), 6));
	}

	public function testLowerGamma()
	{
		$this->assertEquals(0.16060, round($this->basic->lowerGamma(3, 1), 5));
		$this->assertEquals(0.64665, round($this->basic->lowerGamma(3, 2), 5));
		$this->assertEquals(0.91237, round($this->basic->lowerGamma(3, 2.5), 5));
		$this->assertEquals(400.07089, round($this->basic->lowerGamma(10, 3), 5));
		$this->assertEquals(2951.02827, round($this->basic->lowerGamma(10, 4), 5));
		$this->assertEquals(11549.76544, round($this->basic->lowerGamma(10, 5), 5));
		$this->assertEquals(30454.34729, round($this->basic->lowerGamma(10, 6), 5));
	}
	
	public function testIlowerGamma()
	{
		$this->assertEquals(1, round($this->basic->ilowerGamma(3, 0.16060), 5));
		$this->assertEquals(2, round($this->basic->ilowerGamma(3, 0.64665), 5));
		$this->assertEquals(2.5, round($this->basic->ilowerGamma(3, 0.91237), 5));
		$this->assertEquals(3, round($this->basic->ilowerGamma(10, 400.07089), 5));
		$this->assertEquals(4, round($this->basic->ilowerGamma(10, 2951.02827), 5));
		$this->assertEquals(5, round($this->basic->ilowerGamma(10, 11549.76544), 5));
		$this->assertEquals(6, round($this->basic->ilowerGamma(10, 30454.34729), 5));
	}
	
	public function testUpperGamma()
	{
		$this->assertEquals(1.83940, round($this->basic->upperGamma(3, 1), 5));
		$this->assertEquals(1.35335, round($this->basic->upperGamma(3, 2), 5));
		$this->assertEquals(1.08763, round($this->basic->upperGamma(3, 2.5), 5));
		$this->assertEquals(362479.92911, round($this->basic->upperGamma(10, 3), 5));
		$this->assertEquals(359928.97173, round($this->basic->upperGamma(10, 4), 5));
		$this->assertEquals(351330.23456, round($this->basic->upperGamma(10, 5), 5));
		$this->assertEquals(332425.65271, round($this->basic->upperGamma(10, 6), 5));
	}

	public function testBeta()
	{
		$this->assertEquals(1, round($this->basic->beta(1, 1), 2));
		$this->assertEquals(0.5, round($this->basic->beta(1, 2), 2));
		$this->assertEquals(0.5, round($this->basic->beta(2, 1), 2));
		$this->assertEquals(0.0015873, round($this->basic->beta(5, 5), 7));
		$this->assertEquals(0.0002525, round($this->basic->beta(5, 8), 7));
	}

	public function testRegularizedIncompleteBeta()
	{
		$this->assertEquals(0.25, round($this->basic->regularizedIncompleteBeta(1, 1, 0.25), 5));
		$this->assertEquals(0.43750, round($this->basic->regularizedIncompleteBeta(1, 2, 0.25), 5));
		$this->assertEquals(0.06250, round($this->basic->regularizedIncompleteBeta(2, 1, 0.25), 5));
		$this->assertEquals(0.73343, round($this->basic->regularizedIncompleteBeta(5, 5, 0.6), 5));
		$this->assertEquals(0.94269, round($this->basic->regularizedIncompleteBeta(5, 8, 0.6), 5));
	}

	public function testIregularizedIncompleteBeta()
	{
		$this->assertEquals(0.25, round($this->basic->iregularizedIncompleteBeta(1, 1, 0.25), 5));
		$this->assertEquals(0.25, round($this->basic->iregularizedIncompleteBeta(1, 2, 0.43750), 5));
		$this->assertEquals(0.25, round($this->basic->iregularizedIncompleteBeta(2, 1, 0.06250), 5));
		$this->assertEquals(0.6, round($this->basic->iregularizedIncompleteBeta(5, 5, 0.73343), 5));
		$this->assertEquals(0.6, round($this->basic->iregularizedIncompleteBeta(5, 8, 0.94269), 5));
	}

	public function testPermutations()
	{
		$this->assertEquals(1, $this->basic->permutations(1, 1));
		$this->assertEquals(2, $this->basic->permutations(2, 1));
		$this->assertEquals(12, $this->basic->permutations(4, 2));
		$this->assertEquals(120, $this->basic->permutations(5, 5));
		$this->assertEquals(6720, $this->basic->permutations(8, 5));
	}

	public function testCombinations()
	{
		$this->assertEquals(1, $this->basic->combinations(1, 1));
		$this->assertEquals(2, $this->basic->combinations(2, 1));
		$this->assertEquals(6, $this->basic->combinations(4, 2));
		$this->assertEquals(1, $this->basic->combinations(5, 5));
		$this->assertEquals(56, $this->basic->combinations(8, 5));
	}
}
/* End of File */
