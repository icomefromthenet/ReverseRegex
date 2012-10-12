<?php
namespace PHPStats;

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

use PHPStats\Exception as PHPStatsException;

/**
* Stats class
* 
* Static class containing a variety of useful statistical functions.  
* Fills in where PHP's math functions fall short.  Many functions are
* used extensively by the probability distributions.
*
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
* @since 0.0.1
*/
class BasicStats
{
    
    
    /**
      *  Useful to tell if a float has a mathematically integer value.
      *
      *  @access protected
      *  @return boolean true if and integer
      *  @param mixed $x
      */
    protected function is_integer($x)
    {
	return ($x == floor($x));
    }
    
    /**
      * Evaluates the continued fraction for incomplete beta function by modified Lentz's method.
      * Is a factored-out portion of the implementation of the regularizedIncompleteBeta
      *
      * @access protected
      */
    protected function betacf($x, $a, $b)
    {
	$fpmin = 1e-30;
	# These q's will be used in factors that occur in the coefficients
	$qab   = $a + $b;
	$qap   = $a + 1;
	$qam   = $a - 1;
	$c     = 1;
	$d     = 1 - $qab * $x / $qap;
	
	if(abs($d) < $fpmin ) {
		$d = $fpmin;
	}
	
	$d     = 1 / $d;
	$h     = $d;
	
	for ($m = 1; $m <= 100; $m++) {
	    $m2 = 2 * $m;
	    $aa = $m * ($b - $m) * $x / (($qam + $m2) * ($a + $m2));

	    # One step (the even one) of the recurrence
	    $d = 1 + $aa * $d;
	    
	    if(abs($d) < $fpmin ) {
		$d = $fpmin;
	    }
	    
	    $c = 1 + $aa / $c;
	    
	    if(abs($c) < $fpmin ) {
		$c = $fpmin;
	    }
	    
	    $d  = 1 / $d;
	    $h *= $d * $c;
	    $aa = -($a + $m) * ($qab + $m) * $x / (($a + $m2) * ($qap + $m2));

	    # Next step of the recurrence (the odd one)
	    $d = 1 + $aa * $d;
	    
	    if(abs($d) < $fpmin) {
		$d = $fpmin;
	    }
	    
	    $c = 1 + $aa / $c;
	    
	    if(abs($c) < $fpmin) {
		$c = $fpmin;
	    }
	    
	    $d   = 1 / $d;
	    $del = $d * $c;
	    $h  *= $del;

	    if(abs($del - 1.0) < 3e-7 ) break;
	}
	return $h;
    }
    

    /**
     * Sum Function
     * 
     * Sums an array of numeric values.  Non-numeric values
     * are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The sum of the elements of the array
     */
    public function sum(array $data)
    {
	$sum = 0.0;
	
	foreach ($data as $element) {
	    if (is_numeric($element) === true) {
		$sum += $element;
	    }
	}
	
	return $sum;
    }

    /**
     * Product Function
     * 
     * Multiplies an array of numeric values.  Non-numeric values
     * are treated as ones.
     * 
     * @param array $data An array of numeric values
     * @return float The product of the elements of the array
     */
    public function product(array $data)
    {
	$product = 1;
	
	foreach ($data as $element) {
	    if (is_numeric($element) === true) {
		$product *= $element;
	    }
	}
	
	return $product;
    }

    /**
     * Average Function
     * 
     * Takes the arithmetic mean of an array of numeric values.
     * Non-numeric values are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The arithmetic average of the elements of the array
     */
    public function average(array $data)
    {
	return $this->sum($data) / count($data);
    }

    /**
     * Geometric Average Function
     * 
     * Takes the geometic mean of an array of numeric values.
     * Non-numeric values are treated as ones.
     * 
     * @param array $data An array of numeric values
     * @return float The geometic average of the elements of the array
     */
    public function gaverage(array $data)
    {
	return pow($this->product($data), 1/count($data));
    }

    /**
     * Sum-Squared Function
     * 
     * Returns the sum of squares of an array of numeric values.
     * Non-numeric values are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The arithmetic average of the elements of the array
     */
    public function sumsquared(array $data)
    {
	$sum = 0.0;
	
	foreach ($data as $element) {
	    if (is_numeric($element) === true) {
		$sum += pow($element, 2);
	    }
	}
	
	return $sum;
    }


    /**
     * Sum-XY Function
     * 
     * Returns the sum of products of paired variables in a pair of arrays
     * of numeric values.  The two arrays must be of equal length.
     * Non-numeric values are treated as zeroes.
     * 
     * @param array $datax An array of numeric values
     * @param array $datay An array of numeric values
     * @return float The products of the paired elements of the arrays
     */
    public function sumXY(array $datax, array $datay)
    {
	$n = min(count($datax), count($datay));
	$sum = 0.0;
	
	for ($count = 0; $count < $n; $count++) {
	    if (is_numeric($datax[$count]) === true) {
		$x = $datax[$count];
	    }
	    else {
		$x = 0; //Non-numeric elements count as zero.
	    }

	    if (is_numeric($datay[$count]) === true) {
		$y = $datay[$count];
	    }
	    else {
	    	$y = 0; //Non-numeric elements count as zero.
	    }
	    $sum += $x*$y;
	}
	
	return $sum;
    }

    /**
     * Sum-Squared Error Function
     * 
     * Returns the sum of squares of errors of an array of numeric values.
     * Non-numeric values are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The sum of the squared errors of the elements of the array
     */
    public function sse(array $data)
    {
	$average = $this->average($data);
	$sum = 0.0;
	
	foreach ($data as $element) {
	    if (is_numeric($element) === true) {
		$sum += pow($element - $average, 2);
	    }
	    else {
		$sum += pow(0 - $average, 2);
	    }
	}
	
	return $sum;
    }

    /**
     * Mean-Squared Error Function
     * 
     * Returns the arithmetic mean of squares of errors of an array
     * of numeric values. Non-numeric values are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The average squared error of the elements of the array
     */
    public function mse(array $data)
    {
	return $this->sse($data) / count($data);
    }

    /**
     * Covariance Function
     * 
     * Returns the covariance of two arrays.  The two arrays must
     * be of equal length. Non-numeric values are treated as zeroes.
     * 
     * @param array $datax An array of numeric values
     * @param array $datay An array of numeric values
     * @return float The covariance of the two supplied arrays
     */
    public function covariance(array $datax, array $datay)
    {
	return ($this->sumXY($datax, $datay) / count($datax)) - ($this->average($datax) * $this->average($datay));
    }

    /**
     * Variance Function
     * 
     * Returns the population variance of an array.
     * Non-numeric values are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The variance of the supplied array
     */
    public function variance(array $data)
    {
	return $this->covariance($data, $data);
    }

    /**
     * Standard Deviation Function
     * 
     * Returns the population standard deviation of an array.
     * Non-numeric values are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The population standard deviation of the supplied array
     */
    public function stddev(array $data)
    {
	return sqrt($this->variance($data));
    }

    /**
     * Sample Standard Deviation Function
     * 
     * Returns the sample (unbiased) standard deviation of an array.
     * Non-numeric values are treated as zeroes.
     * 
     * @param array $data An array of numeric values
     * @return float The unbiased standard deviation of the supplied array
     */
    public function sampleStddev(array $data)
    {
	return sqrt($this->sse($data) / (count($data)-1));
    }

    /**
     * Correlation Function
     * 
     * Returns the correlation of two arrays.  The two arrays must
     * be of equal length. Non-numeric values are treated as zeroes.
     * 
     * @param array $datax An array of numeric values
     * @param array $datay An array of numeric values
     * @return float The correlation of the two supplied arrays
     */
    public function correlation($datax, $datay)
    {
	return $this->covariance($datax, $datay) / ($this->stddev($datax) * $this->stddev($datay));
    }

    /**
     * Factorial Function
     * 
     * Returns the factorial of an integer.  Values less than 1 return
     * as 1.  Non-integer arguments are evaluated only for the integer
     * portion (the floor).  
     * 
     * @param int $x An array of numeric values
     * @return int The factorial of $x, i.e. x!
     */
    public function factorial($x)
    {
	$sum = 1;
	
	for ($i = 1; $i <= floor($x); $i++) {
	    $sum *= $i;
	}
	
	return $sum;
    }
    
    /**
     * Error Function
     * 
     * Returns the real error function of a number.
     * An approximation from Abramowitz and Stegun is used.
     * Maximum error is 1.5e-7. More information can be found at
     * http://en.wikipedia.org/wiki/Error_function#Approximation_with_elementary_functions
     * 
     * @param float $x Argument to the real error function
     * @return float A value between -1 and 1
     */
    public function erf($x)
    {
	if ($x < 0) {
	    return - $this->erf(-$x);
	}

	$t = 1 / (1 + 0.3275911 * $x);
	return 1 - (0.254829592*$t - 0.284496736*pow($t, 2) + 1.421413741*pow($t, 3) + -1.453152027*pow($t, 4) + 1.061405429*pow($t, 5))*exp(-pow($x, 2));
    }
    
    /**
     * Inverse Error Function
     * 
     * Returns the inverse real error function of a number.
     * More information can be found at
     * http://en.wikipedia.org/wiki/Error_function#Inverse_function
     * 
     * @param float $x Argument to the real error function
     * @return float A value between -1 and 1
     */
    public function ierf($x)
    {
	//To increase accuracy, keep adding on terms from the series expansion.
	return 1/2 * pow(M_PI, 0.5) * ($x + M_PI*pow($x, 3)/12 + 7*pow(M_PI, 2)*pow($x, 5)/480 + 127*pow(M_PI, 3)*pow($x, 7)/40320 + 4369*pow(M_PI, 4)*pow($x, 9)/5806080 + 34807*pow(M_PI, 5)*pow($x, 11)/182476800);
    }
    
    /**
     * Gamma Function
     * 
     * Returns the gamma function of a number.
     * The gamma function is a generalization of the factorial function
     * to non-integer and negative non-integer values. 
     * The relationship is as follows: gamma(n) = (n - 1)!
     * Stirling's approximation is used.  Though the actual gamma function
     * is defined for negative, non-integer values, this approximation is
     * undefined for anything less than or equal to zero.
     * 
     * @param float $x Argument to the gamma function
     * @return float The gamma of $x
     */
    public function gamma($x)
    {
	//Lanczos' Approximation from Wikipedia
	
	// Coefficients used by the GNU Scientific Library
	$g = 7;
	$p = array(0.99999999999980993, 676.5203681218851, -1259.1392167224028,
		 771.32342877765313, -176.61502916214059, 12.507343278686905,
		 -0.13857109526572012, 9.9843695780195716e-6, 1.5056327351493116e-7);
	 
	$value = null; 
	 
	// Reflection formula
	if ($x < 0.5) {
	    $value =  M_PI / (sin(M_PI*$x) * $this->gamma(1-$x));
	}
	else {
	    $x--;
	    $y = $p[0];
	    
	    for ($i = 1; $i < $g+2; $i++) {
		    $y += $p[$i]/($x+$i);
	    }
	    
	    $t = $x + $g + 0.5;
	    
	    $value = pow(2*M_PI, 0.5) * pow($t, $x+0.5) * exp(-$t) * $y;
	}
	
	return $value;
    }

    /**
     * Log Gamma Function
     * 
     * Returns the natural logarithm of the gamma function.  Useful for
     * scaling.
     * 
     * @param float $x Argument to the gamma function
     * @return float The natural log of gamma of $x
     */
    public function gammaln($x)
    {
	//Thanks to jStat for this one.
	$cof = array(
		76.18009172947146, -86.50532032941677, 24.01409824083091,
		-1.231739572450155, 0.1208650973866179e-2, -0.5395239384953e-5);
	$xx  = $x;
	$y   = $xx;
	$tmp = $x + 5.5;
	$tmp -= ($xx + 0.5) * log($tmp);
	$ser = 1.000000000190015;

	for($j = 0; $j < 6; $j++ ) {
	    $ser += $cof[$j] / ++$y;
	}

	return log( 2.5066282746310005 * $ser / $xx) - $tmp;
    }

    /**
     * Inverse gamma function
     * 
     * Returns the inverse of the gamma function.  The relative error of the
     * principal branch peaks at 1.5 near the lower bound (i.e. igamma(0.885603))
     * and approaches zero the higher the argument to this function.
     * The secondary branch is not fully covered by the approximation and so
     * will have much higher error.
     * 
     * @param float $x The result of the gamma function
     * @param bool $principal True for the principal branch, false for the secondary (e.g. gamma(x) where x < 1.461632)
     * @return float The argument to the gamma function
     * @link http://mathforum.org/kb/message.jspa?messageID=342551&tstart=0
     */
    public function igamma($x, $principal = true)
    {
	if ($x < 0.885603) {
		return NAN;  // gamma(1.461632) == 0.885603, the positive minimum of gamma
	}
	
	//$k = 1.461632;
	$c  = 0.036534; //pow(2*M_PI, 0.5)/M_E - self::gamma($k);
	$lx = log(($x + $c)/2.506628274631); //pow(2*M_PI, 0.5)); == 2.506628274631
	
	return $lx / $this->lambert($lx/M_E, $principal) + 0.5;
    }

    /**
     * Digamma Function
     * 
     * Returns the digamma function of a number
     * 
     * @param float $x Argument to the digamma function
     * @return The result of the digamma function
     * @link http://www.uv.es/~bernardo/1976AppStatist.pdf
     */
    public function digamma($x)
    {
	$s = 1.0e-5;
	$c = 8.5;
	$s3 = 8.33333333e-2;
	$s4 = 8.33333333e-3;
	$s5 = 3.968253968e-2;
	$d1 = -0.5772156649;

	$y = $x;
	$retval = 0;

	if ($y <= 0) {
	    return NAN;
	}

	if ($y <= $s) {
	    return $d1 - 1/$y;
	}

	while ($y < $c) {
	    $retval = $retval - 1/$y;
	    $y++;
	}

	$r = 1/$y;
	$retval = $retval + log($y) - 0.5 * $r;
	$r *= $r;
	$retval = $retval - $r * ($s3 - $r * ($s4 - $r *$s5));

	return $retval;
    }

    /**
     * Lambert Function
     * 
     * Returns the positive branch of the lambert function
     * 
     * @param float $x Argument to the lambert funcction
     * @param bool $principal True to use the principal branch, false to use the secondary
     * @return float The result of the lambert function
     * @link http://www.whim.org/nebula/math/lambertw.html
     */
    public function lambert($x, $principal = true)
    {
	
	if ($principal) {
	    if ($x > 10) $w = log($x) - log(log($x));
	    elseif ($x > -1/M_E) $w = 0;
	    else return NAN; //Undefined below -1/e
	}
	else { //Secondary
	    if ($x >= -1/M_E && $x <= -0.1) $w = -2;
	    elseif ($x > -0.1 && $x < 0) $w = log(-$x) - log(-log(-x));
	    else return NAN; //Defined only for [-1/e, 0)
	}
	
	for ($k = 1; $k < 150; ++$k) {
	    $old_w = $w;
	    $w = ($x*exp(-$w) + pow($w, 2))/($w + 1);
	    
	    if (abs($w - $old_w) < 0.0000001) break;
	}
	
	return $w;
    }
    
    /**
     * Incomplete (Lower) Gamma Function
     * 
     * Returns the lower gamma function of a number.
     * 
     * @param float $s Upper bound of integration
     * @param float $x Argument to the lower gamma function.
     * @return float The lower gamma of $x
     */
    public function lowerGamma($s, $x)
    {
	//Adapted from jStat
	$aln   = $this->gammaln($s);
	$afn   = $this->gamma($s);
	$ap    = $s;
	$sum   = 1 / $s;
	$del   = $sum;
	$afix  = ($s >= 1 )?$s:1 / $s;
	$ITMAX = floor(log($afix) * 8.5 + $s * 0.4 + 17);

	if ($x < 0 || $s <= 0 ) {
	    return NAN;
	}
	elseif ($x < $s + 1 ) {
	    for ($i = 1; $i <= $ITMAX; $i++) {
		    $sum += $del *= $x / ++$ap;
	    }

	    $endval = $sum * exp(-$x + $s * log($x) - ($aln));
	}
	else {
	    $b = $x + 1 - $s;
	    $c = 1 / 1.0e-30;
	    $d = 1 / $b;
	    $h = $d;

	    for ($i = 1; $i <= $ITMAX; $i++) {
		    $an = -$i * ($i - $s);
		    $b  += 2;
		    $d  = $an * $d + $b;
		    $c  = $b + $an / $c;
		    $d  = 1 / $d;
		    $h  *= $d * $c;
	    }

	    $endval = 1 - $h * exp(-$x + $s * log($x) - ($aln));
	}

	return $endval * $afn;
    }
    
    /**
     * Inverse Incomplete (Lower) Gamma Function
     * 
     * Returns the inverse of the lower gamma function of a number.
     * 
     * @param float $s Upper bound of integration
     * @param float $x Result of the lower gamma function.
     * @return float The argument to the lower gamma function that would return $x
     */
    public function ilowerGamma($s, $x)
    {
	$precision = 8;
	$guess = array(0, 20);
	$IT_MAX = 1000;
	$i = 1;

	while (round($guess[$i], $precision) != round($guess[$i - 1], $precision) && $i < $IT_MAX) {
	    $f  = $this->lowerGamma($s, $guess[$i]);
	    $f2 = $this->lowerGamma($s, $guess[$i - 1]);
	    $fp = ($f - $f2) / ($guess[$i] - $guess[$i - 1]);

	    $guess[] = $guess[$i - 1] - $f / $fp;
	    $i++;
	}

	return $guess[$i - 1];
    }
    
    /**
     * Incomplete (Upper) Gamma Function
     * 
     * Returns the upper gamma function of a number.
     * 
     * @param float $s Lower bound of integration
     * @param float $x Argument to the upper gamma function
     * @return float The upper gamma of $x
     */
    public function upperGamma($s, $x)
    {
	return $this->gamma($s) - $this->lowerGamma($s, $x);
    }

    /**
     * Beta Function
     * 
     * Returns the beta function of a pair of numbers.
     * 
     * @param float $a The alpha parameter
     * @param float $b The beta parameter
     * @return float The beta of $a and $b
     */
    public function beta($a, $b)
    {
	return $this->gamma($a) * $this->gamma($b) / $this->gamma($a + $b);
    }
    
    /**
     * Calculates the regularized incomplete beta function.
     * 
     * Implements the jStat method of calculating the incomplete beta.
     * 
     * @param float $a The alpha parameter
     * @param float $b The beta parameter
     * @param float $x Upper bound of integration
     * @return float The incomplete beta of $a and $b, up to $x
     */
    public function regularizedIncompleteBeta($a, $b, $x)
    {
	# Again, thanks to jStat.
	# Factors in front of the continued fraction.
	$value = false;
	
	if ($x > 0 || $x < 1) {
	    if ($x == 0 || $x == 1) {
		$bt = 0;
	    }
	    else {
		$bt = exp($this->gammaln($a + $b) - $this->gammaln($a) - $this->gammaln($b) + $a * log($x) + $b * log(1 - $x));	
	    }

	    if( $x < ( $a + 1 ) / ( $a + $b + 2 ) ) {
		# Use continued fraction directly.
		$value = $bt * $this->betacf($x, $a, $b) / $a;
	    }
	    else {
		# Else use continued fraction after making the symmetry transformation.
		$value = 1 - $bt * $this->betacf(1 - $x, $b, $a) / $b;
	    }	
	}
	
	return $value;
    }
    
    
    /**
     * Inverse Regularized Incomplete Beta Function
     *
     * The inverse of the regularized incomplete beta function.  
     *
     * @param float $a The alpha parameter
     * @param float $b The beta parameter
     * @param float $x The incomplete beta of $a and $b, up to the upper bound of integration
     * @return float Upper bound of integration
     */
    public function iregularizedIncompleteBeta($a, $b, $x)
    {
	//jStat is my hero.
	$EPS = 1e-8;
	$a1 = $a - 1;
	$b1 = $b - 1;

	$lna = $lnb = $pp = $t = $u = $err = $return = $al = $h = $w = $afac = 0;

	if( $x <= 0 ) return 0;
	if( $x >= 1 ) return 1;

	if( $a >= 1 && $b >= 1 ) {
	    $pp = ($x < 0.5) ? $x : 1 - $x;
	    $t = pow(-2 * log($pp), 0.5);
	    $return = (2.30753 + $t * 0.27061) / (1 + $t * (0.99229 + $t * 0.04481)) - $t;
	    
	    if( $x < 0.5 ) $return = -$return;
	    
	    $al = ($return * $return - 3) / 6;
	    $h = 2 / (1 / (2 * $a - 1) + 1 / (2 * $b - 1));
	    $w = ($return * pow($al + $h, 0.5) / $h) - (1 / (2 * $b - 1) - 1 / (2 * $a - 1)) * ($al + 5 / 6 - 2 / (3 * $h));
	    $return = $a / ($a + $b * exp(2 * $w));
	} 
	else {
	    $lna = log($a / ($a + $b));
	    $lnb = log($b / ($a + $b));
	    $t = exp($a * $lna) / $a;
	    $u = exp($b * $lnb) / $b;
	    $w = $t + $u;
	    if($x < $t / $w) $return = pow($a * $w * $x, 1 / $a);
	    else $return = 1 - pow($b * $w * (1 - $x), 1 / $b);
	}

	$afac = -$this->gammaln($a) - $this->gammaln($b) + $this->gammaln($a + $b);
	
	for($j = 0; $j < 10; $j++) {
		
	    if($return === 0 || $return === 1) {
	        break;
	    }
	    
	    $err     = $this->regularizedIncompleteBeta($a, $b, $return) - $x;
	    $t       = exp($a1 * log($return) + $b1 * log(1 - $return) + $afac);
	    $u       = $err / $t;
	    $return -= ($t = $u / (1 - 0.5 * min(1, $u * ($a1 / $return - $b1 / (1 - $return)))));
	    
	    if($return <= 0) {
	        $return = 0.5 * ($return + $t);
	    }
	    if($return >= 1) {
	        $return = 0.5 * ($return + $t + 1);
	    }
	    
	    if(abs($t) < $EPS * $return && $j > 0) {
	        break;
	    }
	}
	
	return $return;
    }

    /**
     * Permutation Function
     * 
     * Returns the number of ways of choosing $r objects from a collection
     * of $n objects, where the order of selection matters.
     * 
     * @param int $n The size of the collection
     * @param int $r The size of the selection
     * @return int $n pick $r
     */
    public function permutations($n, $r)
    {
	return $this->factorial($n) / $this->factorial($n - $r);
    }

    /**
     * Combination Function
     * 
     * Returns the number of ways of choosing $r objects from a collection
     * of $n objects, where the order of selection does not matter.
     * 
     * @param int $n The size of the collection
     * @param int $r The size of the selection
     * @return int $n choose $r
     */
    public function combinations($n, $r)
    {
	return $this->permutations($n, $r) / $this->factorial($r);
    }
    
    
}
/* End of File */