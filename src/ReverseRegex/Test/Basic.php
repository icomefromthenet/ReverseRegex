<?php
namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\PimpleBootstrap;
use Pimple\Pimple;

abstract class Basic extends TestCase
{
    public function createApplication()
    {
        $boot = new PimpleBootstrap(); 
        $pimple = $boot->boot(new Pimple());  
        return $pimple;
    }
    
}
/* End of File */