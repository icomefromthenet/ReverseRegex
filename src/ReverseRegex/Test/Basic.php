<?php
namespace ReverseRegex\Test;

abstract class Basic extends \PHPUnit_Framework_TestCase
{
    
    
    public function createApplication()
    {
        return $_GLOBALS['pimple'];
    }
    
}
/* End of File */