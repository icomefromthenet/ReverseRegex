<?php
namespace PHPStats\Tests\Generator;

use PHPUnit_Framework_TestCase,
    PHPStats\Generator\MersenneRandom;

class MersenneRandomTest extends PHPUnit_Framework_TestCase
{
    
    public function testGeneratorImpementsInterface()
    {
        $this->assertInstanceOf('PHPStats\Generator\GeneratorInterface',new MersenneRandom());
    }
    
    public function testGeneratorDeterministic()
    {
        $gen = new MersenneRandom(1);
        
        $random_1 = $gen->generate(1,10);
        $random_2 = $gen->generate(1,10);
        $random_3 = $gen->generate(1,10);
        $random_4 = $gen->generate(1,10);
        
        $genB = new MersenneRandom(1);
             
        $randomB_1 = $genB->generate(1,10);
        $randomB_2 = $genB->generate(1,10);
        $randomB_3 = $genB->generate(1,10);
        $randomB_4 = $genB->generate(1,10);
             
            
        $this->assertEquals($randomB_1,$random_1);
        $this->assertEquals($randomB_2,$random_2);
        $this->assertEquals($randomB_3,$random_3);
        $this->assertEquals($randomB_4,$random_4);    
                   
    }
    
    public function testGeneratorBoundries()
    {
        $gen = new MersenneRandom(1);
        
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