<?php
namespace ReverseRegex\Random;

use ReverseRegex\Exception as ReverseRegexException;

/**
  *   Mersenne Twiseter Implementation
  *
  *   @author Lewis Dyer <getintouch@icomefromthenet.com>
  *   @link http://boxrefuge.com/?tag=random-number
  */
class MersenneRandom implements GeneratorInterface
{
    /**
      *  @var integer a seed use count 
      */
    protected $index;
    
    /**
      *  @var integer the seed value to use 
      */
    protected $seed;
    
    /**
      *  @var integer previous seed value used
      */
    protected $ps;
    
    /**
      *  @var integer the max 
      */
    protected $max;
    
    /**
      *  @var integer the min 
      */
    protected $min;
    
    public function __construct($seed = null)
    {
        
        $this->seed($seed);
        $this->index = -1;
        $this->ps = null;
        $this->max = null;
        $this->min = null;
    }
    
    
    public function max($value = null)
    {
        if($value === null && $this->max === null) {
            $max = 2147483647;
        }
        elseif($value === null) {
            $max = $this->max;
        }
        else {
            $max = $this->max = $value;
        }
        
        return $max;
    }
    
    
    public function min($value = null)
    {
        if($value === null && $this->max === null) {
            $min = 0;
        }
        elseif($value === null) {
            $min = $this->min;
        }
        else {
            $min = $this->min = $value;
        }
        
        return $min;
    }
    
    public function generate($min = 0,$max = null)
    {
        if($max === null) {
            $max = $this->max;
        }
        
        if($min === null) {
            $min = $this->min;
        }
        
        return abs($this->mt(++$this->index,$min,$max));
    }
    
    
    public function seed($seed = null)
    {
        if($seed === null){
            $seed = mt_rand(0,PHP_INT_MAX);    
        }
        
        $this->seed = $seed;
    }
    
    /**
    * Mersenne Twister Random Number Generator
    * Returns a random number. Depending on the application, you likely don't have to reseed every time as you can simply select a different index
    * from the last seed and get a different number.
    *
    * Note: This has been tweaked for performance. It still generates the same numbers as the original algorithm but it's slightly more complicated
    *       because it maintains both speed and elegance. Though not a crucial difference under normal use, on 1 million iterations,
    *       re-seeding each time, it will save 5 minutes of time from the orginal algorithm - at least on my system.
    *
    * @param $index An index indicating the index of the internal array to select the number to generate the random number from
    * @param $min  The minimum number to return
    * @param $max The maximum number to return
    * @return float the random number
    * @link http://boxrefuge.com/?tag=random-number
    * @author Justin unknown
    * 
    **/
    public function mt($index = null, $min = 0, $max = 1000)
    {
        static $op = array(0x0, 0x9908b0df); // Used for efficiency below to eliminate if statement
        static $mt = array(); // 624 element array used to get random numbers
     
        // Regenerate when reseeding or seeding initially
        if($this->seed !== $this->ps)
        {
            $s = $this->seed & 0xffffffff;
            $mt = array(&$s, 624 => &$s);
            $this->ps = $this->seed;
     
            for($i = 1; $i < 624; ++$i)
                $mt[$i] = (0x6c078965 * ($mt[$i - 1] ^ ($mt[$i - 1] >> 30)) + $i) & 0xffffffff;
     
            // This has been tweaked for maximum speed and elegance
            // Explanation of possibly confusing variables:
            //   $p = previous index
            //   $sp = split parts of array - the numbers at which to stop and continue on
            //   $n = number to iterate to - we loop up to 227 adding 397 after which we finish looping up to 624 subtracting 227 to continue getting out 397 indices ahead reference
            //   $m = 397 or -227 to add to $i to keep our 397 index difference
            //   $i = the previous element in $sp, our starting index in this iteration
            for($j = 1, $sp = array(0, 227, 397); $j < count($sp); ++$j)
            {
                for($p = $j - 1, $i = $sp[$p], $m = ((624 - $sp[$j]) * ($p ? -1 : 1)), $n = ($sp[$j] + $sp[$p]); $i < $n; ++$i)
                {
                    $y = ($mt[$i] & 0x80000000) | ($mt[$i + 1] & 0x7fffffff);
                    $mt[$i] = $mt[$i + $m] ^ ($y >> 1) ^ $op[$y & 0x1];
                }
            }
        }
     
        // Select a number from the array and randomize it
        $y = $mt[$this->index = $this->index % 624];
        $y ^= $y >> 11;
        $y ^= ($y << 7) & 0x9d2c5680;
        $y ^= ($y << 15) & 0xefc60000;
        $y ^= $y >> 18;
     
        return $y % ($max - $min + 1) + $min;
    }
    

    
}
/* End of File */