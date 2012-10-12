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
* Binomial class
* 
* Represents the Binomial distribution, a distribution that represents the
* number of successes in a larger number of Bernoulli trials.
*
* For more information, see: http://en.wikipedia.org/wiki/Binomial_distribution
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
* @since 0.0.4
*/
class Binomial extends BasicCalculator
{
    
    /**
     * Returns a random variate of $n trials at $p probability each
     * 
     * @param float $p The probability of success per trial.
     * @param int $n The number of trials.
     * @return float The random variate.
     */
    public function getRvs($p = 0.5, $n = 1)
    {
	$successes = 0;

	for ($i = 0; $i < $n; $i++) {
	    if ($this->bernoulliTrial($p)) {
		$successes++;
	    }
	}

	return $successes;
    }
    
    /**
     * Returns the probability mass function
     * 
     * @param float $x The test value
     * @param float $p The probability of success per trial
     * @param int $n The number of trials
     * @return float The probability
     */
    public function getPmf($x, $p = 0.5, $n = 1)
    {
	return $this->basic->combinations($n, $x) * pow($p, $x) * pow(1 - $p, $n - $x);
    }
    
    /**
     * Probability Distribution function
     * 
     * Alias for getPmf
     * 
     * @param float $x The test value
     * @param float $lambda The rate of events
     * @return float The probability
     */
    public function getPdf($x, $p = 0.5, $n = 1)
    {
	return $this->getPmf($x, $p, $n);
    }
    
    /**
     * Returns the cumulative distribution function, the probability of getting the test value or something below it
     * 
     * float $x The test value
     * @param float $p The probability of success per trial
     * @param int $n The number of trials
     * @return float The probability
     */
    public function getCdf($x, $p = 0.5, $n = 1)
    {
        $sum = 0.0;
     
        for ($count = 0; $count <= $x; $count++) {
	    $sum += $this->getPmf($count, $p, $n);
        }
    
        return $sum;
    }
    
    /**
     * Returns the survival function, the probability of getting the test value or something above it
     * 
     * @param float $x The test value
     * @param float $p The probability of success per trial
     * @param int $n The number of trials
     * @return float The probability
     */
    public function getSf($x, $p = 0.5, $n = 1)
    {
	return 1.0 - $this->getCdf($x, $p, $n);
    }
    
    /**
     * Returns the percent-point function, the inverse of the cdf
     * 
     * @param float $x The test value
     * @param float $p The probability of success per trial
     * @param int $n The number of trials
     * @return float The value that gives a cdf of $x
     */
    public function getPpf($x, $p = 0.5, $n = 1)
    {
	$i = 0;
	$cdf = 0;
	
	while ($cdf < $x) {
	    $cdf += $this->getPmf($i, $p, $n);
	    $i++;
	}
	
	return $i - 1;
    }
    
    /**
     * Returns the inverse survival function, the inverse of the sf
     * 
     * @param float $x The test value
     * @param float $p The probability of success per trial
     * @param int $n The number of trials
     * @return float The value that gives an sf of $x
     */
    public function getIsf($x, $p = 0.5, $n = 1)
    {
	return $this->getPpf(1.0 - $x, $p, $n);
    }
    
    /**
     * Returns the moments of the distribution
     * 
     * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
     * @param float $p The probability of success per trial
     * @param int $n The number of trials
     * @return type array A dictionary containing the first four moments of the distribution
     */
    public function getStats($moments = 'mv', $p = 0.5, $n = 1)
    {
	$return = array();

	if (strpos($moments, 'm') !== FALSE) $return['mean'] = $n*$p;
	if (strpos($moments, 'v') !== FALSE) $return['variance'] = $n*$p*(1-$p);
	if (strpos($moments, 's') !== FALSE) $return['skew'] = (1-2*$p)/sqrt($n*$p*(1-$p));
	if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = (1 - 6*$p*(1 - $p))/($n*$p*(1-$p));
	
	return $return;
    }
}
/* End of File */