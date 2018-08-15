<?php
namespace ReverseRegex\Test;

use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Parser\Short;

class ShortTest extends Basic
{
    
    public function testDigit()
    {
        $lexer = new Lexer('\d');
        $scope = new Scope();
        $parser = new Short();
        $head   = new LiteralScope('lit1',$scope);
        
        $lexer->moveNext();
        $lexer->moveNext();
        
        $parser->parse($head,$scope,$lexer);
        
        $result = $head->getLiterals();
        
        foreach($result as $value) {
            $this->assertRegExp('/\d/',$value);
        }
        
    }
    
    public function testNotDigit()
    {
        $lexer = new Lexer('\D');
        $scope = new Scope();
        $parser = new Short();
        $head   = new LiteralScope('lit1',$scope);
        
        $lexer->moveNext();
        $lexer->moveNext();
        
        $parser->parse($head,$scope,$lexer);
        
        $result = $head->getLiterals();
        
        foreach($result as $value) {
            $this->assertRegExp('/\D/',$value);
        }
        
    }
    
    public function testWhitespace()
    {
        $lexer = new Lexer('\s');
        $scope = new Scope();
        $parser = new Short();
        $head   = new LiteralScope('lit1',$scope);
        
        $lexer->moveNext();
        $lexer->moveNext();
        
        $parser->parse($head,$scope,$lexer);
        
        $result = $head->getLiterals();
        
        foreach($result as $value) {
            $this->assertTrue(!empty($value));
        }
    }
    
    public function testNonWhitespace()
    {
        $lexer = new Lexer('\S');
        $scope = new Scope();
        $parser = new Short();
        $head   = new LiteralScope('lit1',$scope);
        
        $lexer->moveNext();
        $lexer->moveNext();
        
        $parser->parse($head,$scope,$lexer);
        
        $result = $head->getLiterals();
        
        foreach($result as $value) {
            $this->assertTrue(!empty($value));
        }
    }
    
    public function testWord()
    {
        $lexer = new Lexer('\w');
        $scope = new Scope();
        $parser = new Short();
        $head   = new LiteralScope('lit1',$scope);
        
        $lexer->moveNext();
        $lexer->moveNext();
        
        $parser->parse($head,$scope,$lexer);
        
        $result = $head->getLiterals();
        
        foreach($result as $value) {
            $this->assertRegExp('/\w/',$value);
        }
        
    }
    
    
    public function testNonWord()
    {
         $lexer = new Lexer('\W');
        $scope = new Scope();
        $parser = new Short();
        $head   = new LiteralScope('lit1',$scope);
        
        $lexer->moveNext();
        $lexer->moveNext();
        
        $parser->parse($head,$scope,$lexer);
        
        $result = $head->getLiterals();
        
        foreach($result as $value) {
            $this->assertRegExp('/\W/',$value);
        }
        
        
    }
  
  
  
    public function testDotRange()
    {
        $lexer = new Lexer('.');
        $scope = new Scope();
        $parser = new Short();
        $head   = new LiteralScope('lit1',$scope);
        
        $lexer->moveNext();

        $parser->parse($head,$scope,$lexer);
        
        $result = $head->getLiterals();
        
        
        // match 0..127 char in ASSCI Chart
        $this->assertCount(128,$result);
        
        
    }
    
    
}
/* End of File */