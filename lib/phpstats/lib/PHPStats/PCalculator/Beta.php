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
* Beta class
* 
* Represents the Beta distribution, a distribution that represents the
* probability distribution of success given an observed series of Bernoulli
* trials.
*
* For more information, see: http://en.wikipedia.org/wiki/Beta_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
* @since 0.0.4
* 
*/
class Beta extends BasicCalculator
{
    /**
      *  @var Gamma 
      */
    protected $gamma;
    
    /**
     * Constructor function
     * 
     * @param Gamma $gamma the gamma calculator
     * @param GeneratorInterface $gen instance of generator
     * @param BasicStats $stats instance of the basic stats calculator
     */
    public function __construct(Gamma $gamma, GeneratorInterface $gen, BasicStats $stats)
    {
	$this->gamma = $gamma;
	    
	parent::__construct($gen,$stats);
    }
    
    /**
     * Returns a random float between $alpha and $alpha plus $beta
     * 
     * @param float $alpha The minimum parameter. Default 0.0
     * @param float $beta The maximum parameter. Default 1.0
     * @return float The random variate.
     */
    public function getRvs($alpha = 1, $beta = 1)
    {
	$x = $this->gamma->getRvs($alpha, 1);
	$y = $this->gamma->getRvs($beta, 1);
	
	return $x/($x + $y);
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @param float $alpha The minimum parameter. Default 0.0
     * @param float $beta The maximum parameter. Default 1.0
     * @return float The probability
     */
    public function getPdf($x, $alpha = 1, $beta = 1)
    {
	$return = 0.0;
	
	if ($x >= 0 && $x <= 1) {
	    $return = pow($x, $alpha - 1) * pow(1 - $x, $beta - 1) / $this->basic->beta($alpha, $beta);
	}
	
	return $return;
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @param float $alpha The minimum parameter. Default 0.0
     * @param float $beta The maximum parameter. Default 1.0
     * @return float The probability
     */
    public function getCdf($x, $alpha = 1, $beta = 1)
    {
	return $this->basic->regularizedIncompleteBeta($alpha, $beta, $x);
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @param float $alpha The minimum parameter. Default 0.0
     * @param float $beta The maximum parameter. Default 1.0
     * @return float The probability
     * @static
     */
    public function getSf($x, $alpha = 1, $beta = 1)
    {
	return 1.0 - $this->getCdf($x, $alpha, $beta);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @param float $alpha The minimum parameter. Default 0.0
     * @param float $beta The maximum parameter. Default 1.0
     * @return float The value that gives a cdf of $x
     */
    public function getPpf($x, $alpha = 1, $beta = 1)
    {
	return $this->basic->iregularizedIncompleteBeta($alpha, $beta, $x);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @param float $alpha The minimum parameter. Default 0.0
     * @param float $beta The maximum parameter. Default 1.0
     * @return float The value that gives an sf of $x
     */
    public function getIsf($x, $alpha = 1, $beta = 1)
    {
	return $this->getPpf(1.0 - $x, $alpha, $beta);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @param float $alpha The minimum parameter. Default 0.0
     * @param float $beta The maximum parameter. Default 1.0
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function getStats($moments = 'mv', $alpha = 1, $beta = 1)
    {
	    $return = array();
	    
	    if (strpos($moments, 'm') !== FALSE) $return['mean'] = $alpha/($beta + $alpha);
	    if (strpos($moments, 'v') !== FALSE) $return['variance'] = ($alpha*$beta)/(pow($alpha + $beta, 2)*($alpha + $beta + 1));
	    if (strpos($moments, 's') !== FALSE) $return['skew'] = (2*($beta - $alpha)*sqrt($alpha + $beta + 1))/(($alpha + $beta + 2)*sqrt($alpha * $beta));
	    if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = (6*(pow($alpha - $beta, 2)*($alpha + $beta + 1) - $alpha*$beta*($alpha + $beta + 2)))/($alpha*$beta*($alpha + $beta + 2)*($alpha + $beta + 3));
	    
	    return $return;
    }
}
/* End of File */