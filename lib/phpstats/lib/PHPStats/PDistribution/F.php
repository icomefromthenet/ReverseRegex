<?php
namespace PHPStats\PDistribution;

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
    PHPStats\PCalculator\F as FCalculator;

/**
* F class
* 
* Represents the F distribution, which is frequently used as the null
* distribution of a test statistic, such as the analysis of variance.
*
* For more information, see: http://en.wikipedia.org/wiki/F_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
*/
class F implements ProbabilityDistributionInterface
{
    private $d1;
    private $d2;
    
    /**
    *  @var FCalculator
    */
    protected $calculator;
    
    
    /**
     * Constructor function
     * 
     * @param int $d1 Degrees of freedom
     * @param int $d2 Degrees of freedom
     */
    public function __construct($d1 = 1, $d2 = 1, FCalculator $cal)
    {
	$this->d1         = $d1;
	$this->d2         = $d2;
	$this->calculator = $cal;
    }
    
    /**
     * Returns a random float between $d1 and $d1 plus $d2
     * 
     * @return float The random variate.
     */
    public function rvs()
    {
	return $this->calculator->getRvs($this->d1, $this->d2);
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function pdf($x)
    {
	return $this->calculator->getPdf($x, $this->d1, $this->d2);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function cdf($x)
    {
	return $this->calculator->getCdf($x, $this->d1, $this->d2);
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function sf($x)
    {
	return $this->calculator->getSf($x, $this->d1, $this->d2);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @return float The value that gives a cdf of $x
     */
    public function ppf($x)
    {
	return $this->calculator->getPpf($x, $this->d1, $this->d2);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @return float The value that gives an sf of $x
     */
    public function isf($x)
    {
	return $this->calculator->getIsf($x, $this->d1, $this->d2);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function stats($moments = 'mv')
    {
	return $this->calculator->getStats($moments, $this->d1, $this->d2);
    }

}
/* End of File */