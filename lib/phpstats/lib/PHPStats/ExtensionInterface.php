<?php
namespace PHPStats;

/**
  *  Used to add extensions to factorys
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  */
interface ExtensionInterface
{
    
    /**
      *  Registers a single extension
      *
      *  @param string the extension alias
      *  @param string the namespace of the class
      *  @return void
      *  @access public
      *  @static
      */
    public static function registerExtension($index,$namespace);
    
    /**
      *  Registers multiple extensions
      *
      *  @param array list of class to register ($alais => $namespace)
      *  @return void
      *  @access public
      *  @static
      */
    public static function registerExtensions(array $extension);
    
}
/* End of File */