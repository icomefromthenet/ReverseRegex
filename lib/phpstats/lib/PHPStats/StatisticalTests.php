<?php
namespace PHPStats;

/**
* PHP Statistics Library
*
* Copyright (C) 2011-2012 Michael Cordingley<Michael.Cordingley@gmail.com>
* 
* This library is free software; you can redistribute it and/or modify
* it under the terms of the GNU Library General Public License as published
* by the Free Software Foundation; either version 3 of the License, or 
* (at your option) any later version.
* 
* This library is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Library General Public
* License for more details.
* 
* You should have received a copy of the GNU Library General Public License
* along with this library; if not, write to the Free Software Foundation, 
* Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
* 
* LGPL Version 3
*
* @package PHPStats
*/

use PHPStats\Exception as PHPStatsException,
    PHPStats\BasicStats,
    PHPStats\PCalculator\ChiSquare as ChiSquareCalculator,
    PHPStats\PCalculator\StudentsT as StudentsTCalculator;

/**
* StatisticalTests class
* 
* This class contains static functions representing the various statistical 
* tests frequently performed in a frequentist context.  Such tests normally
* are interpreted as a boolean pass or fail, but this obscures a lot of
* information.  As a rule, these tests will instead report a probability
* measure, or p value, which the user then will interpret.  Often, this
* will be checking whether the reported value is less than 0.05, greater 
* than 0.95, or both.
*
* @todo The implementation of this class is still experimental and may change.
*/
class StatisticalTests
{
    /**
      *  BasicStats 
      */
    protected $basic;
    
    /**
      *  @var ChiSquareCalculator 
      */
    protected $chi;
    
    /**
      *  @var StudentsTCalculator
      */
    protected $stu;
    
    
    /**
      *  Class Constructor
      *
      *  @return void
      *  @access public
      *  @param BasicStats $stats
      */
    public function __construct(BasicStats $stats, ChiSquareCalculator $chi , StudentsTCalculator $stu)
    {
	$this->basic = $stats;
	$this->chi   = $chi;
	$this->stu   = $stu;
    }
    
    /**
     * One Sample T Test
     * 
     * The one-sample T test tests whether a sample mean is significantly 
     * different from a stated population mean.
     * 
     * @param array $data The sample to be tested
     * @param float $populationAverage The population average to test against
     * @return float The probability of having the sample's T statistic assuming the given population average
     */
    public function oneSampleTTest(array $data, $populationAverage = 0)
    {
	$sampleT = ($this->basic->average($data) - $populationAverage ) / ($this->basic->sampleStddev($data) / sqrt(count($data)));
	
	return $this->stu->getCdf($sampleT, count($data)-1);
    }

    /**
     * Two Sample T Test
     * 
     * The two-sample T test tests whether two samples are significantly
     * different from each other.
     * 
     * @param array $datax The first sample to test
     * @param array $datay The second sample to test
     * @return float The probability of having the first sample's population mean be greater than or equal to the second sample's population mean
     */
    public function twoSampleTTest(array $datax, array $datay) {
	$df      = pow(pow($this->basic->sampleStddev($datax), 2)/count($datax)+pow($this->basic->sampleStddev($datay), 2)/count($datay), 2)/(pow(pow($this->basic->sampleStddev($datax), 2)/count($datax), 2)/(count($datax)-1)+pow(pow($this->basic->sampleStddev($datay), 2)/count($datay), 2)/(count($datay)-1));
	$sampleT = ($this->basic->average($datax)-$this->basic->average($datay))/sqrt(pow($this->basic->sampleStddev($datax), 2)/count($datax)+pow($this->basic->sampleStddev($datay), 2)/count($datay));
    
	return $this->stu->getCdf($sampleT, $df);
    }

    /**
     * Paired T Test
     * 
     * The paired T test tests whether there is a significant change between
     * two different samples of data where the observations in each sample
     * are paired with each other, such as through repeated measures.
     * 
     * @param array $datax The first sample to test
     * @param array $datay The second sample to test
     * @param float $populationAverage The expected average difference between the two samples.  Defaults to zero for testing for a simple difference.
     * @return float The probability of having the difference in the sample's population means be less than or equal to the stated population average
     */
    public function pairedTTest(array $datax, array $datay, $populationAverage = 0)
    {
	    $data = array();
	    
	    for ($count = 0; $count < min(count($datax), count($datay)); $count++) {
		    $data[$count] = $datax[$count] - $datay[$count];
	    }
	    
	    return $this->oneSampleTTest($data, $populationAverage);
    }
    
    /**
     * Pearson's Chi-Squared Goodness of Fit Test
     * 
     * Tests whether a set of observations is significantly different than a
     * set of expected values. To test goodness of fit against a distribution,
     * use the appropriate distribution class to generate a corresponding set
     * of expected values before calling this.
     * 
     * @param array $observations The set of observations to be tested
     * @param array $expected The set of expected values to be tested
     * @param int $df The degrees of freedom in the test
     * @return float The probability of getting the chi-squared statistic or more, the p-value
     */
    public function chiSquareTest(array $observations, array $expected, $df)
    {
	$sum = 0;

	$pairsTested = min(count($observations), count($observations));
	
	for ($i = 0; $i < $pairsTested; $i++) {
	    if ($expected[$i] == 0) {
	    continue;
	    }

	    $sum += pow($observations[$i] - $expected[$i], 2)/$expected[$i];
	}
	
	return $this->chi->getSf($sum, $df);
    }

    /**
     * Kolmogorov-Smirnov Test
     * 
     * Tests whether a collection of random variates conform to the given
     * distribution.
     * 
     * @param array $observations The collection of random variates
     * @param ProbabilityDistribution $distribution An object representing a continuous distribution
     * @return float The probability of getting our test statistic or more, the p-value
     */
    public function kolmogorovSmirnov(array $observations, $distribution)
    {
	$n = count($observations);
	$d = 0; //Our test statistic
	sort($observations);

	for ($i = 1; $i <= $n; $i++) {
	    $cdf = $distribution->cdf($observations[$i - 1]);
	    $d = max($d, abs(($i)/$n - $cdf), $cdf - ($i - 1) / $n);
	}

	return (1 - $this->kolmogorovCDF(sqrt($n) * $d));
    }

    /**
     * Kolmogorov CDF
     * 
     * Tests whether a collection of random variates conform to the given
     * distribution.
     * 
     * @param float $x The K test statistic
     * @return float The probability of getting our test statistic or less
     */
    public function kolmogorovCDF($x)
    {
	$sum = 0;

	for ($i = 1; $i < 8; $i++) {
	    $sum += exp(-pow(2 * $i - 1, 2) * pow(M_PI, 2) / (8 * pow($x, 2)));
	}

	return sqrt(2 * M_PI) * $sum / $x;
    }
}
