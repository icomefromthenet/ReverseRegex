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
      *  Return true if setting been activated
      *
      *  @access public
      *  @return boolean true
      */
    public function usingAlternatingStrategy();
        
}
/* End of File */