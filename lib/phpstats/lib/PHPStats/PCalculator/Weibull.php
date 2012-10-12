<?php
namespace PHPStats\PCalculator;

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
    PHPStats\Generator\GeneratorInterface;


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
* @since 0.0.4
*/
class Weibull extends BasicCalculator
{
    /**
      *  @var Exponential 
      */
    protected $exp;
    
    
    /**
      *  Class Constructor
      *
      *  @param GeneratorInterface $gen
      *  @param BasicStats $stats
      */    
    public function __construct(GeneratorInterface $gen, BasicStats $stats, Exponential $exp)
    {
	$this->exp = $exp;
	parent::__construct($gen,$stats);
    }
    
    
    /**
     * Returns a random float between $lambda and $lambda plus $k
     * 
     * @param float $lambda The scale parameter. Default 1.0
     * @param float $k The shape parameter. Default 1.0
     * @return float The random variate.
     */
    public function getRvs($lambda = 1, $k = 1)
    {
	$e = $this->exp->getRvs(1);
	return ($e == 0)? 0 : $lambda * pow($e, 1/$k);
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @param float $lambda The scale parameter. Default 1.0
     * @param float $k The shape parameter. Default 1.0
     * @return float The probability
     */
    public function getPdf($x, $lambda = 1, $k = 1)
    {
	$return = 0.0;
	 
	if ($x >= 0) {
	    $return = ($k / $lambda) * pow($x / $lambda, $k - 1)*exp(-pow($x / $lambda, $k));
	}
	
	return $return;
	 
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @param float $lambda The scale parameter. Default 1.0
     * @param float $k The shape parameter. Default 1.0
     * @return float The probability
     */
    public function getCdf($x, $lambda = 1, $k = 1)
    {
	return 1 - exp(-pow($x / $lambda, $k));
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @param float $lambda The scale parameter. Default 1.0
     * @param float $k The shape parameter. Default 1.0
     * @return float The probability
     */
    public function getSf($x, $lambda = 1, $k = 1)
    {
	return 1.0 - $this->getCdf($x, $lambda, $k);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @param float $lambda The scale parameter. Default 1.0
     * @param float $k The shape parameter. Default 1.0
     * @return float The value that gives a cdf of $x
     */
    public function getPpf($x, $lambda = 1, $k = 1)
    {
	return $lambda * pow(-log(1 - $x), 1 / $k);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @param float $lambda The scale parameter. Default 1.0
     * @param float $k The shape parameter. Default 1.0
     * @return float The value that gives an sf of $x
     */
    public function getIsf($x, $lambda = 1, $k = 1)
    {
	return $this->getPpf(1.0 - $x, $lambda, $k);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @param float $lambda The scale parameter. Default 1.0
     * @param float $k The shape parameter. Default 1.0
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function getStats($moments = 'mv', $lambda = 1, $k = 1)
    {
	$return = array();
	
	if (strpos($moments, 'm') !== FALSE) {
	    $return['mean'] = $lambda * $this->basic->gamma(1 + 1/$k);
	}
	
	if (strpos($moments, 'v') !== FALSE) {
	    $return['variance'] = pow($lambda, 2) * $this->basic->gamma(1 + 2/$k) - pow($return['mean'], 2);
	}
	
	if (strpos($moments, 's') !== FALSE) {
	    $return['skew'] = ( $this->basic->gamma(1 + 3/$k) * pow($lambda, 3) - 3 * $return['mean'] * $return['variance'] - pow($return['mean'], 3)) / pow($return['variance'], 1.5);
	}
	
	if (strpos($moments, 'k') !== FALSE) {
	    $return['kurtosis'] = (-6 * pow( $this->basic->gamma(1 + 1/$k), 4) + 12 * pow($this->basic->gamma(1 + 1/$k), 2) * $this->basic->gamma(1 + 2/$k) - 3 * pow($this->basic->gamma(1 + 2/$k), 2) - 4 * $this->basic->gamma(1 + 1/$k) * $this->basic->gamma(1 + 3/$k) + $this->basic->gamma(1 + 4/$k)) / pow($this->basic->gamma(1 + 2/$k) - pow($this->basic->gamma(1 + 1/$k), 2), 2);
	}
	
	return $return;
    }
}
/* End of File */