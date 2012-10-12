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
    PHPStats\PCalculator\Hypergeometric as HypergeometricCalculator;


/**
* Hypergeometric class
* 
* Represents the hypergeometric distribution, which is the probability of
* selecting a certain number of objects of interest from a population with
* some larger number of objects of interest.
* 
* For more information, see: http://en.wikipedia.org/wiki/Hypergeometric_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
*/
class Hypergeometric implements ProbabilityDistributionInterface
{
    private $L;
    private $m;
    private $n;
    
    
    /**
      *  @var HypergeometricCalculator
      */
    protected $calculator;
    
    
    /**
     * Constructor function
     * 
     * @param int $L The population size
     * @param int $m The number of interesting elements in the population
     * @param int $n The number of draws from the population.
     */
    public function __construct($L = 1, $m = 1, $n = 1, HypergeometricCalculator $cal)
    {
	$this->L          = $L;
	$this->m          = $m;
	$this->n          = $n;
	$this->calculator = $cal;
    }
    
    /**
     * Returns a random float between $minimum and $minimum plus $maximum
     * 
     * @return float The random variate.
     */
    public function rvs()
    {
	return $this->calculator->getRvs($this->L, $this->m, $this->n);
    }
    
    /**
     * Returns the probability mass function
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function pmf($x)
    {
	return $this->calculator->getPmf($x, $this->L, $this->m, $this->n);
    }
    
    /**
     * Probability Distribution function
     * 
     * Alias for pmf
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function pdf($x)
    {
	return $this->calculator->pmf($x);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function cdf($x)
    {
	return $this->calculator->getCdf($x, $this->L, $this->m, $this->n);
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function sf($x)
    {
	return $this->calculator->getSf($x, $this->L, $this->m, $this->n);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @return float The value that gives a cdf of $x
     */
    public function ppf($x)
    {
	return $this->calculator->getPpf($x, $this->L, $this->m, $this->n);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @return float The value that gives an sf of $x
     */
    public function isf($x)
    {
	return $this->calculator->getIsf($x, $this->L, $this->m, $this->n);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function stats($moments = 'mv')
    {
	return $this->calculator->getStats($moments, $this->L, $this->m, $this->n);
    }
    
    
}
/* End of File */