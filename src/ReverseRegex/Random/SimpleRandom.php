<?php
namespace ReverseRegex\Random;

use ReverseRegex\Exception as ReverseRegexException;

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
      *  @var integer the max 
      */
    protected $max;
    
    /**
      *  @var integer the min 
      */
    protected $min;
     
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
            throw new ReverseRegexException('Max param has exceeded the maxium 2796203');
        }
        
        if ($this->seed == 0) {
            $this->seed(mt_rand());
        }
        
        $this->seed = ($this->seed * 125) % 2796203;  
        
        
        return $this->seed % ($max - $min + 1) + $min;  
    }  
    
}
/* End of File */