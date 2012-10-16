<?php
namespace ReverseRegex\Generator;

/**
  *  Allows a scope to select children using alternating strategy
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface AlternateInterface
{
    /**
      *  Tell the scope to select childing use alternating strategy
      *
      *  @access public
      *  @return void
      */    
    public function useAlternatingStrategy();
    
    /**
      *  Set the alternating position to zero
      *
      *  @access public
      *  @return void
      */
    public function resetAlternatingPosition();
    
    /**
      *  Increment the alternating position by one
      *
      *  @access public
      *  @return void
      */
    public function incrementAlternatingPosition();
    
    /**
      *  Fetch the alternating position
      *
      *  @access public
      *  @return integer the position
      */
    public function getAlternatingPosition();
        
}
/* End of File */