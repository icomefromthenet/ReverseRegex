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
    PHPStats\PCalculator\Binomial as BinomialCalculator;
    
/**
* Binomial class
* 
* Represents the Binomial distribution, a distribution that represents the
* number of successes in a larger number of Bernoulli trials.
*
* For more information, see: http://en.wikipedia.org/wiki/Binomial_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
*/
class Binomial implements ProbabilityDistributionInterface
{
    private $n;
    private $p;
    
    /**
      *  @var BinomialCalculator 
      */
    protected $binomialCalculator;
    
    /**
     * Constructor function
     *
     * @param float $p The probability of success in a single trial
     * @param int $n The number of trials
     */
    public function __construct($p = 0.5, $n = 1,BinomialCalculator $cal)
    {
	$this->p                  = $p;
	$this->n                  = $n;
	$this->binomialCalculator = $cal;
    }
    
    /**
     * Returns a random variate of $n trials at $p probability each
     * 
     * @return float The random variate.
     */
    public function rvs()
    {
	return $this->binomialCalculator->getRvs($this->p, $this->n);
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function pmf($x)
    {
	return $this->binomialCalculator->getPmf($x, $this->p, $this->n);
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
	return $this->binomialCalculator->getPmf($x);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function cdf($x)
    {
	return $this->binomialCalculator->getCdf($x, $this->p, $this->n);
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function sf($x)
    {
	return $this->binomialCalculator->getSf($x, $this->p, $this->n);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @return float The value that gives a cdf of $x
     */
    public function ppf($x)
    {
	return $this->binomialCalculator->getPpf($x, $this->p, $this->n);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @return float The value that gives an sf of $x
     */
    public function isf($x)
    {
	return $this->binomialCalculator->getIsf($x, $this->p, $this->n);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function stats($moments = 'mv')
    {
	return $this->binomialCalculator->getStats($moments, $this->p, $this->n);
    }
    
}
/* End of File */