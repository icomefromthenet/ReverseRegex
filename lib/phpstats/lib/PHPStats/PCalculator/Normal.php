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
 * Normal class
 * 
 * Represents the normal distribution, which represents the average of a large
 * sample of observations.  
 * 
 * For more information, see: http://en.wikipedia.org/wiki/Normal_distribution
 *
 * @author Michael Cordingley <Michael.Cordingley@gmail.com>
 * @since 0.0.4
 */
class Normal extends BasicCalculator
{
	
   
    /**
     * Returns a normally-distributed random float
     * 
     * @param float $mu The location parameter. Default 0.0
     * @param float $variance The scale parameter. Default 1.0
     * @return float The random variate.
     */
    public function getRvs($mu = 0.0, $variance = 1.0)
    {
	$u = $this->randFloat();
	$v = $this->randFloat();
	
	return $mu + sqrt($variance) * sqrt(-2 * log($u)) * cos(2 * M_PI * $v);
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @param float $mu The location parameter. Default 0.0
     * @param float $variance The scale parameter. Default 1.0
     * @return float The probability
     */
    public function getPdf($x, $mu = 0.0, $variance = 1.0)
    {
	return exp(-pow($x - $mu, 2)/(2*$variance))/sqrt(2*M_PI*$variance);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @param float $mu The location parameter. Default 0.0
     * @param float $variance The scale parameter. Default 1.0
     * @return float The probability
     */
    public function getCdf($x, $mu = 0.0, $variance = 1.0)
    {
	
	return (1 + $this->basic->erf(($x - $mu)/sqrt(2*$variance)))/2;
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @param float $mu The location parameter. Default 0.0
     * @param float $variance The scale parameter. Default 1.0
     * @return float The probability
     * @static
     */
    public function getSf($x, $mu = 0.0, $variance = 1.0)
    {
	return 1.0 - $this->getCdf($x, $mu, $variance);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @param float $mu The location parameter. Default 0.0
     * @param float $variance The scale parameter. Default 1.0
     * @return float The value that gives a cdf of $x
     * @static
     */
    public function getPpf($x, $mu = 0.0, $variance = 1.0)
    {
	return pow(2 * $variance, 0.5) * $this->basic->ierf(2 * $x - 1) + $mu;
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @param float $mu The location parameter. Default 0.0
     * @param float $variance The scale parameter. Default 1.0
     * @return float The value that gives an sf of $x
     * @static
     */
    public function getIsf($x, $mu = 0.0, $variance = 1.0)
    {
	return $this->getPpf(1.0 - $x, $mu, $variance);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @param float $mu The location parameter. Default 0.0
     * @param float $variance The scale parameter. Default 1.0
     * @return type array A dictionary containing the first four moments of the distribution
     * @static
     */
    public function getStats($moments = 'mv', $mu = 0.0, $variance = 1.0)
    {
	    $return = array();
	    
	    if (strpos($moments, 'm') !== FALSE) $return['mean'] = $mu;
	    if (strpos($moments, 'v') !== FALSE) $return['variance'] = $variance;
	    if (strpos($moments, 's') !== FALSE) $return['skew'] = 0;
	    if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = 0;
	    
	    return $return;
    }
    
}
/* End of File */