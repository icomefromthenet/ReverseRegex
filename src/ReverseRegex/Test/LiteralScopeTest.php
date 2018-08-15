<?php

namespace ReverseRegex\Test;

use ReverseRegex\Generator\LiteralScope;

class LiteralScopeTest extends Basic
{


    public function testExtendsScope()
    {
       $literal = new LiteralScope('scope1'); 
       $this->assertInstanceOf('ReverseRegex\Generator\Scope',$literal); 
    }


    public function testAddLiteral()
    {
        $literal = new LiteralScope('scope1');
        
        $literal->addLiteral('a');
        $literal->addLiteral('b');
        $literal->addLiteral('c');
        
        $collection = $literal->getLiterals();
        
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection',$collection);
        
        $this->assertEquals('a',$collection->get(0));
        $this->assertEquals('b',$collection->get(1));
        $this->assertEquals('c',$collection->get(2));
        
    }

    
    public function testGenerateNoRepeats()
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->setMinOccurances(1);
        $literal->setMaxOccurances(1);
        
        $generator_mock = $this->createMock('ReverseRegex\Random\GeneratorInterface', array('generate','seed','max'));
        
        $generator_mock->expects($this->exactly(0))
                       ->method('generate');
                       
        $result = '';
        $literal->generate($result,$generator_mock);
        
        $this->assertEquals('a',$result);
    }
    
    
    public function testGenerateRepeatsTwice()
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->addLiteral('b');
        $literal->setMinOccurances(2);
        $literal->setMaxOccurances(2);
        
        $generator_mock = $this->createMock('ReverseRegex\Random\GeneratorInterface', array('generate','seed','max'));
        
        $generator_mock->expects($this->exactly(2))
                       ->method('generate')
                       ->with($this->equalTo(1),$this->equalTo(2))
                       ->will($this->returnValue(0));
                       
        $result = '';
        
        $literal->generate($result,$generator_mock);
        
        $this->assertEquals('aa',$result);
    }
    
    
    public function testGenerateWithSmallRange()
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->setMinOccurances(1);
        $literal->setMaxOccurances(2);
        
        $gen = new \ReverseRegex\Random\SrandRandom(0);
                       
        $result = '';
        $literal->generate($result,$gen);
        
        $this->assertLessThanOrEqual(2, strlen($result));
        $this->assertGreaterThanOrEqual(1, strlen($result));
        
    }
    
    
    public function testGenerateWithMulipleLiterals()
    {
        $literal = new LiteralScope('scope1');
        $literal->addLiteral('a');
        $literal->addLiteral('b');
        $literal->addLiteral('c');
        $literal->addLiteral('d');
        
        $literal->setMinOccurances(1);
        $literal->setMaxOccurances(4);
        
        $gen = new \ReverseRegex\Random\SrandRandom(0);
        
        $result = '';                       
        $literal->generate($result,$gen);               
        $this->assertLessThanOrEqual(4, strlen($result));
        $this->assertGreaterThanOrEqual(1, strlen($result));
    }
    
    public function testSetLiteral()
    {
        $literal = new LiteralScope('scope1');
        $literal->setLiteral('0001','a');
        $literal->setLiteral('0002','b');
        $literal->setLiteral('0003','c');
        $literal->setLiteral('0004','d');
        
        $result = $literal->getLiterals()->toArray();
        
        $this->assertArrayHasKey('0001',$result);
        $this->assertArrayHasKey('0002',$result);
        $this->assertArrayHasKey('0003',$result);
        $this->assertArrayHasKey('0004',$result);
    }
    
}
/* End of File */