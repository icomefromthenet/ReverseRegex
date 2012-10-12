<?php
namespace PHPStats\PDistribution;

/**
  *  Public interface for ProbabilityDistributions
  *
  *  @author Michael Cordingley <Michael.Cordingley@gmail.com>
  *  @since 0.0.4
  */
interface ProbabilityDistributionInterface
{
    /**
	 * Returns a random float between $mu and $mu plus $variance
	 * 
	 * @return float The random variate.
	 */
	public function rvs(); 
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pdf($x); 
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function cdf($x);
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function sf($x); 
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives a cdf of $x
	 */
	public function ppf($x); 
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives an sf of $x
	 */
	public function isf($x);
        
        /**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @return type array A dictionary containing the first four moments of the distribution
	 */
        public function stats($moments = 'mv');
	
    
}
/* End of File */