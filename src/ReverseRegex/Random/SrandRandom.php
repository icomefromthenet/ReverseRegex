<?php
namespace ReverseRegex\Random;

use ReverseRegex\Exception as ReverseRegexException;

/*
 * class SrandRandom
 *
 * Wrapper to mt_random with seed option
 *
 * Won't work when suhosin.srand.ignore = Off or suhosin.mt_srand.ignore = Off
 * is set. 
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * 
 */
class SrandRandom implements GeneratorInterface
{
    
    /**
      *  @var integer the seed to use on each pass 
      */
    protected $seed;
    
    
     /**
      *  @var integer the max 
      */
    protected $max;
    
    /**
      *  @var integer the min 
      */
    protected $min;
    
    
    /*
     * __construct()
     *
     * @param integer $seed the starting seed
     * @return void
     * @access public
     */
    public function __construct($seed = 0)
    {
        $this->seed($seed);
    }
    
    /**
      *  Return the maxium random number
      *
      *  @access public
      *  @return double
      */
    public function max($value = null)
    {
        if($value === null && $this->max === null) {
            $max = getrandmax();
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
    
    /**
      *  Generate a value between $min - $max
      *
      *  @param integer $max
      *  @param integer $max 
      */
    public function generate($min = 0,$max = null)
    {
        if($max === null) {
            $max = $this->max;
        }
        
        if($min === null) {
            $min = $this->min;
        }
        
        
        return rand($min,$max);
    }
    
    /**
      *  Set the seed to use
      * 
      *  @param $seed integer the seed to use
      *  @access public
      */
    public function seed($seed = null)
    {
        $this->seed = $seed;
        srand($this->seed);
        
        return $this;
    }
    
}
/* End of File */
