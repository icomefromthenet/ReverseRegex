<?php
namespace PHPStats\Generator;

use PHPStats\ExtensionInterface,
    PHPStats\Exception as PHPStatsException;

/**
  *   Generator Factory
  *
  *   @author Lewis Dyer <getintouch@icomefromthenet.com>
  */    
class GeneratorFactory implements ExtensionInterface
{
    
    
    /**
      *  @var string[] list of Generators
      *
      *  Each Generator must implement the PHPStats\GeneratorInterface
      */
    protected static $types = array(
        'srand'     => '\PHPStats\Generator\SrandRandom',
        'mersenne'  => '\PHPStats\Generator\MersenneRandom',
        'simple'    => '\PHPStats\Generator\SimpleRandom',
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
      *  @return PHPStats\GeneratorInterface
      *  @throws PHPStats\Exception
      */
    public function create($type,$seed = null)
    {
        $type = strtolower($type);
        
        # check extension list
        
        if(isset(self::$types[$type]) === true) {
            # assign platform the full namespace
            if(class_exists(self::$types[$type]) === false) {
                throw new PHPStatsException('Unknown Generator at::'.$type);    
            }
            
            $type = self::$types[$type];
            
        } else {
            throw new PHPStatsException('Unknown Generator at::'.$type);
        }
       
        return new $type($seed);
    }
    
}
/* End of File */