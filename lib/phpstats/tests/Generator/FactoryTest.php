<?php
namespace PHPStats\Tests\Generator;

use PHPUnit_Framework_TestCase,
    PHPStats\Generator\GeneratorFactory;

class FactoryTest extends PHPUnit_Framework_TestCase
{
    
    public function testSrandGenerator()
    {
        $factory = new GeneratorFactory();
        $this->assertInstanceOf('\PHPStats\Generator\SrandRandom',$factory->create('srand',null));
    }
    
    public function testSimpleGenerator()
    {
        $factory = new GeneratorFactory();
        $this->assertInstanceOf('\PHPStats\Generator\SimpleRandom',$factory->create('simple',null));
    }
    
    public function testMersenneGenerator()
    {
        $factory = new GeneratorFactory();
        $this->assertInstanceOf('\PHPStats\Generator\MersenneRandom',$factory->create('mersenne',null));
        
    }
    
}
/* End of File */