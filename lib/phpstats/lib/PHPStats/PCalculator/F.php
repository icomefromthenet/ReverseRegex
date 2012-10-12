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
* F class
* 
* Represents the F distribution, which is frequently used as the null
* distribution of a test statistic, such as the analysis of variance.
*
* For more information, see: http://en.wikipedia.org/wiki/F_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
* @since 0.0.4
*/
class F extends BasicCalculator
{
    
    /**
      *  @var ChiSquare 
      */
    protected $chi;
    
    /**
      *  Class Constructor
      *
      *  @param GeneratorInterface $gen
      *  @param BasicStats $stats
      *  @param ChiSquare instance of ChiSquare Calculator
      */    
    public function __construct(GeneratorInterface $gen, BasicStats $stats, ChiSquare $chi)
    {
	$this->chi = $chi;
	
	parent::__construct($gen,$stats);
    }
    
    
    /**
     * Returns a random float between $d1 and $d1 plus $d2
     * 
     * @param float $d1 Degrees of freedom 1. Default 1.0
     * @param float $d2 Degrees of freedom 2. Default 1.0
     * @return float The random variate.
     */
    public function getRvs($d1 = 1, $d2 = 1)
    {
	$x = $this->chi->getRvs($d1);
	$y = $this->chi->getRvs($d2);
	return ($x / $d1) / ($y / $d2);
    }
    
    /**
     * Returns the probability distribution function
     * 
     * @param float $x The test value
     * @param float $d1 Degrees of freedom 1. Default 1.0
     * @param float $d2 Degrees of freedom 2. Default 1.0
     * @return float The probability
     */
    public function getPdf($x, $d1 = 1, $d2 = 1)
    {
	return pow((pow($d1 * $x, $d1) * pow($d2, $d2)) / (pow($d1 * $x + $d2, $d1 + $d2)), 0.5) / ($x * $this->basic->beta($d1 / 2, $d2 / 2));
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * @param float $x The test value
     * @param float $d1 Degrees of freedom 1. Default 1.0
     * @param float $d2 Degrees of freedom 2. Default 1.0
     * @return float The probability
     */
    public function getCdf($x, $d1 = 1, $d2 = 1)
    {
	return $this->basic->regularizedIncompleteBeta($d1 / 2, $d2 / 2, ($d1 * $x)/($d1 * $x + $d2));
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @param float $d1 Degrees of freedom 1. Default 1.0
     * @param float $d2 Degrees of freedom 2. Default 1.0
     * @return float The probability
     */
    public function getSf($x, $d1 = 1, $d2 = 1)
    {
	return 1.0 - $this->getCdf($x, $d1, $d2);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @param float $d1 Degrees of freedom 1. Default 1.0
     * @param float $d2 Degrees of freedom 2. Default 1.0
     * @return float The value that gives a cdf of $x
     */
    public function getPpf($x, $d1 = 1, $d2 = 1)
    {
	$iY = $this->basic->iregularizedIncompleteBeta($d1/2, $d2/2, $x);
	return -($d2*$iY) / ($d1 * ($iY - 1));
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @param float $d1 Degrees of freedom 1. Default 1.0
     * @param float $d2 Degrees of freedom 2. Default 1.0
     * @return float The value that gives an sf of $x
     */
    public function getIsf($x, $d1 = 1, $d2 = 1)
    {
	return $this->getPpf(1.0 - $x, $d1, $d2);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @param float $d1 Degrees of freedom 1. Default 1.0
     * @param float $d2 Degrees of freedom 2. Default 1.0
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function getStats($moments = 'mv', $d1 = 1, $d2 = 1)
    {
	    $return = array();
	    
	    if (strpos($moments, 'm') !== FALSE) {
		    if ($d2 > 2) $return['mean'] = $d2 / ($d2 - 2);
		    else $return['mean'] = NAN;
	    }
	    if (strpos($moments, 'v') !== FALSE) {
		    if ($d2 > 4) $return['variance'] = 2 * pow($d2, 2) * ($d1 + $d2 - 2)/($d1 * pow($d2 - 2, 2) * ($d2 - 4));
		    else $return['variance'] = NAN;
	    }
	    if (strpos($moments, 's') !== FALSE) {
		    if ($d2 > 6) $return['skew'] = (2 * $d1 + $d2 -2) * pow(8 * ($d2 - 4), 0.5) / (($d2 - 6) * pow($d1 * ($d1 + $d2 - 2), 0.5));
		    else $return['skew'] = NAN;
	    }
	    if (strpos($moments, 'k') !== FALSE) {
		    if ($d2 > 8) $return['kurtosis'] = 12 * ($d1 * (5 * $d2 - 22) * ($d1 + $d2 - 2) + ($d2 - 4) * pow($d2 - 2, 2)) / ($d1 * ($d2 - 6) * ($d2 - 8) * ($d1 + $d2 - 2));
		    else $return['kurtosis'] = NAN;
	    }
	    
	    return $return;
    }
}
/* End of File */
