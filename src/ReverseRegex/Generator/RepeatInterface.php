<?php
namespace ReverseRegex\Generator;

/**
  *  Represent a group has max and min number of occurances
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface RepeatInterface
{
    /**
      * Fetches the max re-occurances
      *
      * @access public
      * @return integer the maximum number of occurances
      */    
    public function getMaxOccurances();
    
    /**
      *  Sets the maximum re-occurances
      *
      *  @access public
      *  @param integer $num
      */
    public function setMaxOccurances($num);
    
    
    /**
      *  Fetch the Minimum re-occurances
      *
      *  @access public
      *  @return integer
      */
    public function getMinOccurances();
    
    /**
      *  Sets the Minimum number of re-occurances
      *
      *  @access public
      *  @param integer $num
      */
    public function setMinOccurances($num);
    
    /**
      *  Return the occurance range
      *
      *  @access public
      *  @return integer the range
      */
    public function getOccuranceRange();
    
}
/* End of File */