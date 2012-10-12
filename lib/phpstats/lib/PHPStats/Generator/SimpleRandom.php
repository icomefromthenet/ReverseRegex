<?php
namespace PHPStats\Generator;

use PHPStats\Exception as PHPStatsException;

/**
  *  Simple Random
  *
  *  @link http://www.sitepoint.com/php-random-number-generator/
  *  @author Craig Buckler 
  */
class SimpleRandom implements GeneratorInterface
{
    
    /**
      *  @var integer the seed value to use 
      */
    protected $seed = 0;
     
    /**
      *  Constructor
      *
      *  @return void
      *  @access public
      *  @param integer $seed
      */ 
    public function __construct($seed = null)
    {
        if ($seed === null || $seed === 0) {
            $this->seed(mt_rand());
        }
        
        $this->seed($seed);
    }
     
    /**
      *  Return the maxium random value 
      */ 
    public function max()
    {
        return 2147483647;
    }
     
     
    /**
      *  Set the seed to use for generator
      *
      *  @param integer $seed the seed to use
      *  @access public
      */  
    public function seed($seed = null)
    {  
        if($seed === null) {
            $seed = 0;
        }
        
        return $this->seed = abs(intval($seed)) % 9999999 + 1;  
    }  
    
    /**
      *  Generate a random numer
      *
      *  @param integer $max
      *  @param integer $max 2,796,203 largest possible max
      */
    public function generate($min = 0, $max = null)
    {  
        if($max === null) {
            $max = 2796203;
        }
        
        if($max > 2796203) {
            throw new PHPStatsException('Max param has exceeded the maxium 2796203');
        }
        
        if ($this->seed == 0) {
            $this->seed(mt_rand());
        }
        
        $this->seed = ($this->seed * 125) % 2796203;  
        
        return $this->seed % ($max - $min + 1) + $min;  
    }  
    
}
/* End of File */