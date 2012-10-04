<?php
namespace ReverseRegex\Test;

use ReverseRegex\Generator\Scope;
use PHPStats\Generator\MersenneRandom;

class ScopeTest extends Basic
{
    
    public function testScopeImplementsRepeatInterface()
    {
        $scope = new Scope('scope1');
        $this->assertInstanceOf('ReverseRegex\Generator\RepeatInterface',$scope);
    }
    
    public function testScopeImplementsContextInterface()
    {
        $scope = new Scope('scope1');
        $this->assertInstanceOf('ReverseRegex\Generator\ContextInterface',$scope);
    }
    
    public function testScopeExtendsNode()
    {
        $scope = new Scope('scope1');
        $this->assertInstanceOf('GraphGroup\Object\Node',$scope);
    }
    
    public function testScopeParentNode()
    {
        $parent = new Scope('scope1');
        $scope  = new Scope('scope2');
        
        $scope->setParentScope($parent);
        
        $this->assertSame($parent,$scope->getParentScope());
    }
    
    
    public function testRepeatInterface()
    {
        $scope  = new Scope('scope1');
        
        $scope->setMaxOccurances(10);
        $scope->setMinOccurances(5);

        $this->assertEquals(10,$scope->getMaxOccurances());
        $this->assertEquals(5,$scope->getMinOccurances());
        $this->assertEquals(5,$scope->getOccuranceRange());
    }
    
    
    public function testAttachChild()
    {
        
       $scope  = new Scope('scope1');
       $scope2  = new Scope('scope2');
       
       $scope->attach($scope2)->rewind();
       $this->assertEquals($scope2,$scope->current());
    }
    
    
    public function testRepeatQuota()
    {
        $gen = new MersenneRandom(700);
        
        $scope = new Scope('scope1');
        $scope->setMinOccurances(1);
        $scope->setMaxOccurances(6);
        
        $this->assertEquals(4,$scope->calculateRepeatQuota($gen));
        
    }
    
    /**
      *  @expectedException \ReverseRegex\Exception
      *  @expectedExceptionMessage No child scopes to call must be atleast 1
      */
    public function testGenerateErrorNotChildren()
    {
        $gen = new MersenneRandom(700);
        
        $scope = new Scope('scope1');
        $scope->setMinOccurances(1);
        $scope->setMaxOccurances(6);
        
        $result = '';
        
        $scope->generate($result,$gen);
        
    }
    
    
    public function testGenerate()
    {
        
        $gen = new MersenneRandom(700);
        $result = '';
        
        $scope = new Scope('scope1');
        $scope->setMinOccurances(6);
        $scope->setMaxOccurances(6);
        
        $child = $this->getMock('ReverseRegex\Generator\Scope',array('generate'));
        
        $child->expects($this->exactly(6))
            ->method('generate')
            ->with($this->isType('string'),$this->equalTo($gen))
            ->will($this->returnValue('a'));
        
        $scope->attach($child);
        
        $scope->generate($result,$gen);
        
        $this->assertEquals('a',$result);
        
    }
    
}
/* End of File */