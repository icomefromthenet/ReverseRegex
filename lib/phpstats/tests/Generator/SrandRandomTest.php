<?php
namespace PHPStats\Tests\Generator;

use PHPUnit_Framework_TestCase,
    PHPStats\Generator\SrandRandom;

class SrandRandomTest extends PHPUnit_Framework_TestCase
{
    
    public function testGeneratorImpementsInterface()
    {
        $this->assertInstanceOf('PHPStats\Generator\GeneratorInterface',new SrandRandom());
    }
    
    public function testGeneratorDeterministic()
    {
           
    }
    
    public function testGeneratorBoundries()
    {
        $gen = new SrandRandom(1);
        
        $random_1 = $gen->generate(1,10);
        $random_2 = $gen->generate(1,10);
        $random_3 = $gen->generate(1,10);
        $random_4 = $gen->generate(1,10);
        
        $this->assertLessThanOrEqual(10,$random_1);
        $this->assertLessThanOrEqual(10,$random_2);
        $this->assertLessThanOrEqual(10,$random_3);
        $this->assertLessThanOrEqual(10,$random_4);
        
        $this->assertGreaterThanOrEqual(1,$random_1);
        $this->assertGreaterThanOrEqual(1,$random_2);
        $this->assertGreaterThanOrEqual(1,$random_3);
        $this->assertGreaterThanOrEqual(1,$random_4);
    }
   
    
    
    
}
/* End of File */