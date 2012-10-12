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
* Exponential class
* 
* Represents the exponential distribution, which represents the distribution
* of arrival times from a Poisson process.
*
* For more information, see: http://en.wikipedia.org/wiki/Exponential_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
* @since 0.0.4
*/
class Exponential extends BasicCalculator
{

    /**
     * Returns a random float between $minimum and $minimum plus $maximum
     * 
     * @param float $lambda Scale parameter
     * @return float The random variate.
     */
    public function getRvs($lambda = 1)
    {
	return -log($this->randFloat())/$lambda;
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @param float $lambda Scale parameter
     * @return float The probability
     */
    public function getPdf($x, $lambda = 1)
    {
       return $lambda*exp(-$lambda*$x);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @param float $lambda Scale parameter
     * @return float The probability
     */
    public function getCdf($x, $lambda = 1)
    {
        return 1.0 - exp(-$lambda*$x);
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @param float $lambda Scale parameter
     * @return float The probability
     */
    public function getSf($x, $lambda = 1)
    {
        return 1.0 - $this->getCdf($x, $lambda);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @param float $lambda Scale parameter
     * @return float The value that gives a cdf of $x
     */
    public function getPpf($x, $lambda = 1)
    {
        return log(1 - $x) / -$lambda;
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @param float $lambda Scale parameter
     * @return float The value that gives an sf of $x
     */
    public function getIsf($x, $lambda = 1)
    {
        return $this->getPpf(1.0 - $x, $lambda);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @param float $lambda Scale parameter
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function getStats($moments = 'mv', $lambda = 1)
    {
        $return = array();
	    
        if (strpos($moments, 'm') !== FALSE) $return['mean'] = 1.0/$lambda;
        if (strpos($moments, 'v') !== FALSE) $return['variance'] = pow($lambda, -2);
        if (strpos($moments, 's') !== FALSE) $return['skew'] = 2;
        if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = 6;
	    
        return $return;
    }
}
/* End of File */
