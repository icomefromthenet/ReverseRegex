<?php
namespace ReverseRegex\Generator;

use PHPStats\Generator\GeneratorInterface;
use ReverseRegex\Exception as GeneratorException;

/**
  *  Base Class for Scopes
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Scope extends Node implements ContextInterface, RepeatInterface, AlternateInterface
{
    
    const REPEAT_MIN_INDEX  = 'repeat_min';
    
    const REPEAT_MAX_INDEX  = 'repeat_max'; 
    
    const USE_ALTERNATING_INDEX = 'use_alternating';
    
    
    /**
      *  Class Constructor 
      */
    public function __construct($label = 'node')
    {
        parent::__construct($label);

        $this[self::USE_ALTERNATING_INDEX] = false;

        $this->setMinOccurances(1);
        $this->setMaxOccurances(1);
    }
    
    
    //  ----------------------------------------------------------------------------
    # Conext Interface
    
    
    /**
      *  Generate a text string appending to result arguments
      *
      *  @access public
      *  @param string $result
      *  @param GeneratorInterface $generator
      */
    public function generate(&$result,GeneratorInterface $generator)
    {
        if($this->count() === 0) {
            throw new GeneratorException('No child scopes to call must be atleast 1');
        }
        
        $repeat_x = $this->calculateRepeatQuota($generator);
        
        # rewind the current item 
        $this->rewind();
        while($repeat_x > 0) {
            
            if($this->usingAlternatingStrategy()) {
                $randomIndex = \round($generator->generate(1,($this->count())));                
                $this->get($randomIndex)->generate($result,$generator);    
                                
            } else {
                
                foreach($this as $current) {
                    $current->generate($result,$generator);    
                }    
                
            }
            
            $repeat_x = $repeat_x -1;
        }
        
        
        return $result;
    }
    
    
    /**
      *  Fetch a node given an `one-based index`
      *
      *  @access public
      *  @return Scope | null if none found
      */
    public function get($index)
    {
        if($index > $this->count() || $index <= 0) {
            return null;
        }
        
        $this->rewind();
        while(($index - 1) > 0) {
            $this->next();
            $index = $index - 1;
        }
        
        return $this->current();
        
    }
    
    
    //  ----------------------------------------------------------------------------
    # Repeat Interface
    
     /**
      * Fetches the max occurances
      *
      * @access public
      * @return integer the maximum number of occurances
      */    
    public function getMaxOccurances()
    {
        return $this[self::REPEAT_MAX_INDEX];
    }
    
     /**
      *  Sets the maximum re-occurances
      *
      *  @access public
      *  @param integer $num
      */
    public function setMaxOccurances($num)
    {
        if(is_integer($num) === false){
            throw new GeneratorException('Number must be an integer');
        }
        
        $this[self::REPEAT_MAX_INDEX] = $num;
        
    }
    
    /**
      *  Fetch the Minimum Occurances
      *
      *  @access public
      *  @return integer
      */
    public function getMinOccurances()
    {
        return $this[self::REPEAT_MIN_INDEX];
    }
    
    /**
      *  Sets the Minimum number of re-occurances
      *
      *  @access public
      *  @param integer $num
      */
    public function setMinOccurances($num)
    {
        if(is_integer($num) === false){
            throw new GeneratorException('Number must be an integer');
        }
        
        $this[self::REPEAT_MIN_INDEX] = $num;
    }
    
    
     /**
      *  Return the occurance range
      *
      *  @access public
      *  @return integer the range
      */
    public function getOccuranceRange()
    {
        return (integer)($this->getMaxOccurances() - $this->getMinOccurances());
    }
    
    
    /**
      *  Calculate a random numer of repeats given the current min-max range
      *
      *  @access public
      *  @param GeneratorInterface $generator
      *  @return Integer
      */
    public function calculateRepeatQuota(GeneratorInterface $generator)
    {
        $repeat_x = $this->getMinOccurances();
        
        if($this->getOccuranceRange() > 0) {
            $repeat_x = (integer) \round($generator->generate($this->getMinOccurances(),$this->getMaxOccurances()));        
        } 
        
        return $repeat_x;
    }
    
    //------------------------------------------------------------------
    # AlternateInterface
    
    
    /**
      *  Tell the scope to select childing use alternating strategy
      *
      *  @access public
      *  @return void
      */    
    public function useAlternatingStrategy()
    {
        $this[self::USE_ALTERNATING_INDEX] = true;
    }
    
    /**
      *  Return true if setting been activated
      *
      *  @access public
      *  @return boolean true
      */
    public function usingAlternatingStrategy()
    {
        return (boolean) $this[self::USE_ALTERNATING_INDEX];
    }
    
}
/* End of File */