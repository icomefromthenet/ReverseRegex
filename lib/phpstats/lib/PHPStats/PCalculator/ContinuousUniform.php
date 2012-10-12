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
* ContinuousUniform class
* 
* Represents the continuous uniform distribution, a distribution that 
* represents equiprobable outcomes on a continuous space.
*
* For more information, see: http://en.wikipedia.org/wiki/Uniform_distribution_%28continuous%29
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
* @since 0.0.4
*/
class ContinuousUniform extends BasicCalculator
{
    
    /**
     * Returns a random float between $minimum and $minimum plus $maximum
     * 
     * @param float $minimum The minimum parameter. Default 0.0
     * @param float $maximum The maximum parameter. Default 1.0
     * @return float The random variate.
     */
    public function getRvs($minimum = 0.0, $maximum = 1.0)
    {
	return $this->randFloat() * ($maximum-$minimum) + $minimum;
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @param float $minimum The minimum parameter. Default 0.0
     * @param float $maximum The maximum parameter. Default 1.0
     * @return float The probability
     */
    public function getPdf($x, $minimum = 0.0, $maximum = 1.0)
    {
	$return = 0.0;
	
	if ($x >= $minimum && $x <= $maximum) {
	    $return =  1.0 / ($maximum - $minimum);
	}
	    
	return $return;
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @param float $minimum The minimum parameter. Default 0.0
     * @param float $maximum The maximum parameter. Default 1.0
     * @return float The probability
     */
    public function getCdf($x, $minimum = 0.0, $maximum = 1.0)
    {
	$return = 0.0;
	
	if ($x >= $minimum && $x <= $maximum) {
	    $return = ($x - $minimum) / ($maximum - $minimum);
	}
	elseif ($x > $maximum) {
	    $return = 1.0;
	}
    
	return $return;
    
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @param float $minimum The minimum parameter. Default 0.0
     * @param float $maximum The maximum parameter. Default 1.0
     * @return float The probability
     */
    public function getSf($x, $minimum = 0.0, $maximum = 1.0)
    {
	return 1.0 - $this->getCdf($x, $minimum, $maximum);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @param float $minimum The minimum parameter. Default 0.0
     * @param float $maximum The maximum parameter. Default 1.0
     * @return float The value that gives a cdf of $x
     */
    public function getPpf($x, $minimum = 0.0, $maximum = 1.0)
    {
	return $minimum + $x*($maximum - $minimum);
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @param float $minimum The minimum parameter. Default 0.0
     * @param float $maximum The maximum parameter. Default 1.0
     * @return float The value that gives an sf of $x
     */
    public function getIsf($x, $minimum = 0.0, $maximum = 1.0)
    {
	return $this->getPpf(1.0 - $x, $minimum, $maximum);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @param float $minimum The minimum parameter. Default 0.0
     * @param float $maximum The maximum parameter. Default 1.0
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function getStats($moments = 'mv', $minimum = 0.0, $maximum = 1.0)
    {
	$return = array();
	    
	if (strpos($moments, 'm') !== FALSE) $return['mean'] = 0.5*($maximum + $minimum);
	if (strpos($moments, 'v') !== FALSE) $return['variance'] = (1.0/12)*pow(($maximum - $minimum), 2);
	if (strpos($moments, 's') !== FALSE) $return['skew'] = 0;
	if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = -1.2;
	    
	return $return;
    }
}
/* End of File */
