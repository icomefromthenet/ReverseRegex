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
    PHPStats\PCalculator\Weibull as WeibullCalculator;


/**
* Weibull class
* 
* Represents the Weibull distribution, which is frequently used to model
* failure rates.  In essence, it is the exponential distribution, but with
* a rate that increases or decreases linearly over time.  A k of one indicates
* a constant failure rate, whereas less than one indicates a decreasing
* failure rate and a higher k indicates an increasing failure rate.
* 
* For more information, see: http://en.wikipedia.org/wiki/Weibull_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
*/
class Weibull implements ProbabilityDistributionInterface
{
    private $lambda;
    private $k;
    
    /**
      *  @var WeibullCalculator
      */
    protected $calculator;
    
    /**
     * Constructor function
     * 
     * @param float $lambda The scale parameter
     * @param float $k The shape parameter
     */
    public function __construct($lambda = 1, $k = 1, WeibullCalculator $cal)
    {
	$this->lambda     = $lambda;
	$this->k          = $k;
	$this->calculator = $cal;
    }
    
    /**
     * Returns a random float between $lambda and $lambda plus $k
     * 
     * @return float The random variate.
     */
    public function rvs()
    {
	return $this->calculator->getRvs($this->lambda, $this->k);
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function pdf($x)
    {
	return $this->calculator->getPdf($x, $this->lambda, $this->k);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function cdf($x)
    {
	return $this->calculator->getCdf($x, $this->lambda, $this->k);
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function sf($x) 
    {
	return $this->calculator->getSf($x, $this->lambda, $this->k);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @return float The value that gives a cdf of $x
     */
    public function ppf($x)
    {
	return $this->calculator->getPpf($x, $this->lambda, $this->k);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @return float The value that gives an sf of $x
     */
    public function isf($x)
    {
	return $this->calculator->getIsf($x, $this->lambda, $this->k);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function stats($moments = 'mv')
    {
	return $this->calculator->getStats($moments, $this->lambda, $this->k);
    }
    
    
}
/* End of File */
