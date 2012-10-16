<?php
namespace ReverseRegex\Test;

use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use PHPStats\Generator\MersenneRandom;

class ParserTest extends Basic
{
    
    public function testParserExampleA()
    {
        $lexer = new Lexer('ex1{5,5}');
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer,$container,$head);
        $generator = $parser->parse()->getResult();
        
        $result ='';
        $random = new MersenneRandom(100);
        $generator->generate($result,$random);
        
        $this->assertEquals('ex1ex1ex1ex1ex1',$result);
        
    }
    
    
    public function testParserExampleB()
    {
        $lexer = new Lexer('\(0[23478]\)-[0-9]{4} [0-9]{4}');
        
        $container = new Scope();
        $head = new Scope();
        $parser = new Parser($lexer,$container,$head);
        $generator = $parser->parse()->getResult();
        
        $result ='';
        $random = new MersenneRandom(100);
        $generator->generate($result,$random);
        
        var_dump($result);
        
    }
    
}
/* End of File */