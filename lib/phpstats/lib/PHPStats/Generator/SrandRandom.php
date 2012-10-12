<?php
namespace PHPStats\Generator;

use PHPStats\Exception;

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
    
    
    /*
     * __construct()
     *
     * @param integer $seed the starting seed
     * @return void
     * @access public
     */
    public function __construct($seed = 0)
    {
        $this->seed = $this->seed($seed);
    }
    
    /**
      *  Return the maxium random number
      *
      *  @access public
      *  @return double
      */
    public function max()
    {
        return getrandmax();
    }
    
    /**
      *  Generate a value between $min - $max
      *
      *  @param integer $max
      *  @param integer $max 
      */
    public function generate($min = 0,$max = null)
    {
        srand($this->seed);
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
    }
    
}
/* End of File */