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
    PHPStats\PCalculator\Beta as BetaCalculator;

/**
* Beta class
* 
* Represents the Beta distribution, a distribution that represents the
* probability distribution of success given an observed series of Bernoulli
* trials.
*
* For more information, see: http://en.wikipedia.org/wiki/Beta_distribution
*/
class Beta implements ProbabilityDistributionInterface
{
    private $alpha;
    private $beta;
    
    /**
      *  @var BetaCalculator
      */
    protected $betaCalculator;
    
    /**
     * Constructor function
     * 
     * @param float $alpha The alpha parameter
     * @param float $beta The beta parameter
     */
    public function __construct($alpha = 1, $beta = 1, BetaCalculator $cal)
    {
	$this->alpha          = $alpha;
	$this->beta           = $beta;
	$this->betaCalculator = $cal;
    }
    
    /**
     * Returns a random float between $alpha and $alpha plus $beta
     *
     * @return float The random variate.
     */
    public function rvs()
    {
	return $this->betaCalculator->getRvs($this->alpha, $this->beta);
    }
    
    /**
     * Returns the probability distribution function
     *
     * @param float $x The test value
     * @return float The probability
     */
    public function pdf($x)
    {
	return $this->betaCalculator->getPdf($x, $this->alpha, $this->beta);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
    
     * @param float $x The test value
     * @return float The probability
     */
    public function cdf($x)
    {
	return $this->betaCalculator->getCdf($x, $this->alpha, $this->beta);
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @return float The probability
     */
    public function sf($x)
    {
	return $this->betaCalculator->getSf($x, $this->alpha, $this->beta);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @return float The value that gives a cdf of $x
     */
    public function ppf($x)
    {
	return $this->betaCalculator->getPpf($x, $this->alpha, $this->beta);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @return float The value that gives an sf of $x
     */
    public function isf($x)
    {
	return $this->betaCalculator->getIsf($x, $this->alpha, $this->beta);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function stats($moments = 'mv')
    {
	return $this->betaCalculator->getStats($moments, $this->alpha, $this->beta);
    }
    
    
}
/* End of File */