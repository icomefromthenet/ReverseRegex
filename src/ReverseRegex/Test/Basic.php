<?php
namespace ReverseRegex\Test;

use ReverseRegex\PimpleBootstrap;
use Pimple\Pimple;

abstract class Basic extends \PHPUnit_Framework_TestCase
{
    public function createApplication()
    {
        $boot = new PimpleBootstrap(); 
        $pimple = $boot->boot(new Pimple());  
        return $pimple;
    }
    
}
/* End of File */