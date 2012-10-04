<?php
namespace ReverseRegex\Test;

use ReverseRegex\Generator\AlternationScope;
use PHPStats\Generator\MersenneRandom;

class AlternationScopeTest extends Basic
{
    
    public function testScopeImplementsRepeatInterface()
    {
        $scope = new AlternationScope('scope1');
        $this->assertInstanceOf('ReverseRegex\Generator\RepeatInterface',$scope);
    }
    
    public function testScopeImplementsContextInterface()
    {
        $scope = new AlternationScope('scope1');
        $this->assertInstanceOf('ReverseRegex\Generator\ContextInterface',$scope);
    }
    
    public function testScopeExtendsNode()
    {
        $scope = new AlternationScope('scope1');
        $this->assertInstanceOf('GraphGroup\Object\Node',$scope);
    }
    
    public function testScopeParentNode()
    {
        $parent = new AlternationScope('scope1');
        $scope  = new AlternationScope('scope2');
        
        $scope->setParentScope($parent);
        
        $this->assertSame($parent,$scope->getParentScope());
    }
    
    
    public function testRepeatInterface()
    {
        $scope  = new AlternationScope('scope1');
        
        $scope->setMaxOccurances(10);
        $scope->setMinOccurances(5);

        $this->assertEquals(10,$scope->getMaxOccurances());
        $this->assertEquals(5,$scope->getMinOccurances());
        $this->assertEquals(5,$scope->getOccuranceRange());
    }
    
    
    public function testAttachChild()
    {
        
       $scope  = new AlternationScope('scope1');
       $scope2  = new AlternationScope('scope2');
       
       $scope->attach($scope2)->rewind();
       $this->assertEquals($scope2,$scope->current());
    }
    
    
    public function testRepeatQuota()
    {
        $gen = new MersenneRandom(700);
        
        $scope = new AlternationScope('scope1');
        $scope->setMinOccurances(1);
        $scope->setMaxOccurances(6);
        
        $this->assertEquals(4,$scope->calculateRepeatQuota($gen));
        
    }
    
    
    /**
      *  @expectedException \ReverseRegex\Exception
      *  @expectedExceptionMessage There are no values to alternate over
      */
    public function testGenerateErrorNoChildren()
    {
        $gen = new MersenneRandom(700);
        $result = '';
        $scope = new AlternationScope('scope1');
        $scope->setMinOccurances(6);
        $scope->setMaxOccurances(6);
        
        $scope->generate($result,$gen);
    }
    
    
    public function testGenerate()
    {
        $gen = new MersenneRandom(700);
        $result = '';
        
        $scope = new AlternationScope('scope1');
        $scope->setMinOccurances(6);
        $scope->setMaxOccurances(6);
        
        $child1 = $this->getMock('ReverseRegex\Generator\Scope',array('generate'));
        
        $child1->expects($this->exactly(1))
            ->method('generate')
            ->with($this->isType('string'),$this->equalTo($gen))
            ->will($this->returnValue('a'));
            
        $child2 = $this->getMock('ReverseRegex\Generator\Scope',array('generate'));
        
        $child2->expects($this->exactly(5))
            ->method('generate')
            ->with($this->isType('string'),$this->equalTo($gen))
            ->will($this->returnValue('b'));
        
        
        $scope->attach($child1);
        $scope->attach($child2);
        
        $scope->generate($result,$gen);
        
    }
    
}
/* End of File */