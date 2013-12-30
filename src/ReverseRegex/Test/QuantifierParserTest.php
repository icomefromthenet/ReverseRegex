<?php
namespace ReverseRegex\Test;

use ReverseRegex\Lexer;
use ReverseRegex\Parser\Quantifier;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\MersenneRandom;

class QuantifierParserTest extends Basic
{
    
    public function testQuantifierParserPatternA()
    {
        $pattern = '{1,5}';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
        $this->assertEquals(1,$scope->getMinOccurances());
        $this->assertEquals(5,$scope->getMaxOccurances());
            
    }
    
    
    public function testQuantiferSingleValue()
    {
        
        $pattern = '{5}';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
        $this->assertEquals(5,$scope->getMinOccurances());
        $this->assertEquals(5,$scope->getMaxOccurances());
        
    }
    
    public function testQuantiferSpacesIncluded()
    {
        
        $pattern = '{ 1 , 5 }';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
        $this->assertEquals(1,$scope->getMinOccurances());
        $this->assertEquals(5,$scope->getMaxOccurances());
        
    }
    
    /**
      *  @expectedException \ReverseRegex\Exception
      *  @expectedExceptionMessage Quantifier expects and integer compitable string
      */
    public function testFailerAlphaCaracters()
    {
        $pattern = '{ 1 , 5a }';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
    }
    
    /**
      *  @expectedException \ReverseRegex\Exception
      *  @expectedExceptionMessage Quantifier expects and integer compitable string
      */
    public function testFailerMissingMaximumCaracters()
    {
        $pattern = '{ 1 ,}';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
    }
    
     /**
      *  @expectedException \ReverseRegex\Exception
      *  @expectedExceptionMessage Quantifier expects and integer compitable string
      */
    public function testFailerMissingMinimumCaracters()
    {
        $pattern = '{,1}';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
    }
    
    /**
      *  @expectedException \ReverseRegex\Exception
      *  @expectedExceptionMessage Closing quantifier token `}` not found
      */
    public function testMissingClosureCharacter()
    {
        $pattern = '{1,1';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
    }
    
    
    /**
      *  @expectedException \ReverseRegex\Exception
      *  @expectedExceptionMessage Nesting Quantifiers is not allowed
      */
    public function testNestingQuantifiers()
    {
        $pattern = '{1,1{1,1}';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
    }
    
    
    public function testStarQuantifier()
    {
        $pattern = 'az*';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $lexer->moveNext();
        $lexer->moveNext();
        
        $qual->parse($scope,$scope,$lexer);
        
        $this->assertEquals(0,$scope->getMinOccurances());
        $this->assertEquals(PHP_INT_MAX,$scope->getMaxOccurances());
        
    }
    
    
    public function testCrossQuantifier()
    {
        $pattern = 'az+';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $lexer->moveNext();
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
        $this->assertEquals(1,$scope->getMinOccurances());
        $this->assertEquals(PHP_INT_MAX,$scope->getMaxOccurances());
        
    }
    
    public function testQuestionQuantifier()
    {
        $pattern = 'az?';
        $lexer   = new Lexer($pattern);
        $scope   = new Scope();
        $qual    = new Quantifier();
        
        $lexer->moveNext();
        $lexer->moveNext();
        $lexer->moveNext();
        $qual->parse($scope,$scope,$lexer);
        
        $this->assertEquals(0,$scope->getMinOccurances());
        $this->assertEquals(1,$scope->getMaxOccurances());
        
    }
    
    
}
/* End of File */