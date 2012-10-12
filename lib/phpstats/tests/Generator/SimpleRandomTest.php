<?php
namespace PHPStats\Tests\Generator;

use PHPUnit_Framework_TestCase,
    PHPStats\Generator\SimpleRandom;

class SimpleRandomTest extends PHPUnit_Framework_TestCase
{
    
    public function testGeneratorDeterministic()
    {
       
       $simple = new SimpleRandom(1);
       
       $v1 = $simple->generate(1,10);
       $v2 = $simple->generate(1,10);
       $v3 = $simple->generate(1,10);
      
       
       $simple = new SimpleRandom(1);
       
       $b1 = $simple->generate(1,10);
       $b2 = $simple->generate(1,10);
       $b3 = $simple->generate(1,10);
       
       $this->assertEquals($v1,$b1);
       $this->assertEquals($v2,$b2);
       $this->assertEquals($v3,$b3);
        
      
    }
    
    public function testBoundries()
    {
        $simple = new SimpleRandom(1);
       
       $v1 = $simple->generate(1,10);
       $v2 = $simple->generate(1,10);
       $v3 = $simple->generate(1,10);
       
        
       $this->assertLessThanOrEqual(10,$v1);
       $this->assertLessThanOrEqual(10,$v2);
       $this->assertLessThanOrEqual(10,$v3);
       
       $this->assertGreaterThanOrEqual(1,$v1);
       $this->assertGreaterThanOrEqual(1,$v2);
       $this->assertGreaterThanOrEqual(1,$v3);
        
    }
    
    /**
      *  @expectedException \PHPStats\Exception
      *  @expectedExceptionMessage Max param has exceeded the maxium 2796203
      */
    public function testGeneratorThrowsExceptionBadMax()
    {
       $simple = new SimpleRandom(1);
       $v1 = $simple->generate(1,2796204);
    }
    
    public function testImplementsGeneratorInterface()
    {
        $this->assertInstanceOf('\PHPStats\Generator\GeneratorInterface',new SimpleRandom());
    }
    
}
/* End of File */