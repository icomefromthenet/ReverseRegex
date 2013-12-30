<?php
namespace ReverseRegex\Random;

use ReverseRegex\Exception as ReverseRegexException;

/**
  *   Generator Factory
  *
  *   @author Lewis Dyer <getintouch@icomefromthenet.com>
  */    
class GeneratorFactory 
{
    
    
    /**
      *  @var string[] list of Generators
      *
      *  Each Generator must implement the ReverseRegex\RandomInterface
      */
    protected static $types = array(
        'srand'     => '\\ReverseRegex\\Random\\SrandRandom',
        'mersenne'  => '\\ReverseRegex\\Random\\MersenneRandom',
        'simple'    => '\\ReverseRegex\\Random\\SimpleRandom',
    );
    
    public static function registerExtension($index,$namespace)
    {
        $index = strtolower($index);
        return self::$types[$index] = $namespace;
    }
    
    public static function registerExtensions(array $extension)
    {
        foreach($extension as $key => $ns) {
            self::registerExtension($key,$ns);
        }
    }
    
    //  ----------------------------------------------------------------------------
    
     /**
      *  Resolve a Dcotrine DataType Class
      *
      *  @param string the random generator type name
      *  @access public
      *  @return ReverseRegex\RandomInterface
      *  @throws PHPStats\Exception
      */
    public function create($type,$seed = null)
    {
        $type = strtolower($type);
        
        # check extension list
        
        if(isset(self::$types[$type]) === true) {
            # assign platform the full namespace
            if(class_exists(self::$types[$type]) === false) {
                throw new ReverseRegexException('Unknown Generator at::'.$type);    
            }
            
            $type = self::$types[$type];
            
        } else {
            throw new ReverseRegexException('Unknown Generator at::'.$type);
        }
       
        return new $type($seed);
    }
    
}
/* End of File */