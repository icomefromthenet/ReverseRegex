<?php
namespace ReverseRegex\Test;

use ReverseRegex\Exception as RegexException;
use ReverseRegex\Lexer;

class LexerTest extends Basic
{
    public function testInheritsDoctrineLexer()
    {
        $lexer = new Lexer('[a-z]');
        $this->assertInstanceOf('\Doctrine\Common\Lexer',$lexer);
        
    }
    
    public function testLexerPatternA()
    {
        $lexer = new Lexer('[a-z]');
        
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('a',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('-',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_RANGE,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('z',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
        //$lexer->moveNext();
        //$this->assertEquals(null,$lexer->lookahead['value']);
        
    }
    
        
    public function testLexerPatternB()
    {
        $lexer = new Lexer('\[a-z\]');
        
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('a',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('-',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('z',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        //$lexer->moveNext();
        //$this->assertEquals(null,$lexer->lookahead['value']);
        
    }
    
    public function testLexerPatternC()
    {
        $lexer = new Lexer('[1-9]');
        
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('1',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('-',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_RANGE,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('9',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
        //$lexer->moveNext();
        //$this->assertEquals(null,$lexer->lookahead['value']);
        
    }
    
    public function testLexerPatternD()
    {
        $lexer = new Lexer('[1-9\x{56}]');
        
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('1',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('-',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_RANGE,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('9',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('x',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_X,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('{',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('5',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('6',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('}',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
        //$lexer->moveNext();
        //$this->assertEquals(null,$lexer->lookahead['value']);
        
    }
    
    
    public function testLexerPatternE()
    {
        $lexer = new Lexer('([^1-8\[]){0,9}*?+');
        
        $lexer->moveNext();
        $this->assertEquals('(',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_GROUP_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('^',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_NEGATED,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('1',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('-',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_RANGE,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('8',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(')',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_GROUP_CLOSE,$lexer->lookahead['type']);
        
         $lexer->moveNext();
        $this->assertEquals('{',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_QUANTIFIER_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('0',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(',',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('9',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('}',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_QUANTIFIER_CLOSE,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('*',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_QUANTIFIER_STAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('?',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_QUANTIFIER_QUESTION,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('+',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_QUANTIFIER_PLUS,$lexer->lookahead['type']);
        
        //$lexer->moveNext();
        //$this->assertEquals(null,$lexer->lookahead['value']);
        
    }
    
    
    public function testParrentShortCodes()
    {
        $lexer = new Lexer('\W');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals('W',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_NOT_W,$lexer->lookahead['type']);
        
        $lexer = new Lexer('\w');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals('w',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_W,$lexer->lookahead['type']);
        
        $lexer = new Lexer('\S');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals('S',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_NOT_S,$lexer->lookahead['type']);
        
          $lexer = new Lexer('\s');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals('s',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_S,$lexer->lookahead['type']);
        
        $lexer = new Lexer('\D');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals('D',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_NOT_D,$lexer->lookahead['type']);
        
         $lexer = new Lexer('\d');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals('d',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_D,$lexer->lookahead['type']);
        
        
        
    }
    
    public function testLexerPatternF()
    {
        $lexer = new Lexer('[\']');
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        # in the above expression using php metasequence \' to escape a single quote
        # the reg only see the expression ['] and NOT [\']
        $lexer->moveNext();
        $this->assertEquals("'",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
    }
    
    
    public function testLexerEscapedBlackslash()
    {
        $lexer = new Lexer('\\\\');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
    }
    
    
    public function testLexerBrackets()
    {
        $lexer = new Lexer('[\p{}]');
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals("\\",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals("p",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_P,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals("{",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals("}",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
        $lexer = new Lexer('\p{}');
        
        $lexer->moveNext();
        $this->assertEquals("\\",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals("p",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_P,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals("{",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_QUANTIFIER_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals("}",$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_QUANTIFIER_CLOSE,$lexer->lookahead['type']);
                
    }
    
    
    public function testAlternation()
    {
        $lexer = new Lexer('A|a');
        
        $lexer->moveNext();
        $this->assertEquals('A',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('|',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_CHOICE_BAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('a',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        # no alternation in char classes
        
        $lexer = new Lexer('[A|a]');
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('A',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('|',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('a',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
    }
    
    
    public function testDotCharacter()
    {
        
        $lexer = new Lexer('.');
        
        $lexer->moveNext();
        $this->assertEquals('.',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_DOT,$lexer->lookahead['type']);
        
        $lexer = new Lexer('\.');
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('.',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        # normal char in a char class 
        $lexer = new Lexer('[.]');
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('.',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
    }
    
    public function testLexerPatternG()
    {
        $lexer = new Lexer('abcd&\*\(\)');
        
        $lexer->moveNext();
        $this->assertEquals('a',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('b',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('c',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('d',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('&',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('*',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('(',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(')',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
    }
    
    
    public function testUnicodePropertyinCharacterClass()
    {
        $lexer = new Lexer('[np\p{L}]');
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);

        $lexer->moveNext();
        $this->assertEquals('n',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('p',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('p',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_P,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('{',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('L',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('}',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
    }
    
    
    public function testUnicodeReferencePropertyinCharacterClass()
    {
        
        $lexer = new Lexer('[no\X{00FF}]');
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);

        $lexer->moveNext();
        $this->assertEquals('n',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('o',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('X',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SHORT_UNICODE_X,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('{',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('0',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('0',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_NUMERIC,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('F',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('F',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('}',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
    }
    
    
    public function testCarretAndDollar()
    {
        $lexer = new Lexer('^$');
        
        $lexer->moveNext();
        $this->assertEquals('^',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_START_CARET,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('$',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_END_DOLLAR,$lexer->lookahead['type']);
        
        $lexer = new Lexer('[\^$]');
        
        $lexer->moveNext();
        $this->assertEquals('[',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('\\',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_ESCAPE_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('^',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR, $lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('$',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_LITERAL_CHAR,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(']',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_SET_CLOSE,$lexer->lookahead['type']);
        
    }
    
    public function testLexerPatternHGroupNesting()
    {
        $lexer = new Lexer('(())');
        
        $lexer->moveNext();
        $this->assertEquals('(',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_GROUP_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals('(',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_GROUP_OPEN,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(')',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_GROUP_CLOSE,$lexer->lookahead['type']);
        
        $lexer->moveNext();
        $this->assertEquals(')',$lexer->lookahead['value']);
        $this->assertEquals(Lexer::T_GROUP_CLOSE,$lexer->lookahead['type']);
        
    }
    
    
    public function testGroupNestingErrorStillOpen()
    {
        $this->expectException(RegexException::class);        
        $this->expectExceptionMessage('Opening group char "(" has no matching closing character');

        $lexer = new Lexer('(()');
        
    }
    
   
    public function testGroupNestingErrorClosedNotOpened()
    {
        $this->expectException(RegexException::class);        
        $this->expectExceptionMessage('Closing group char "(" has no matching opening character');

        
        $lexer = new Lexer('())');
        
    }
    
     
    public function testCharSetNestingError()
    {
        $this->expectException(RegexException::class);        
        $this->expectExceptionMessage("Can't have a second character class while first remains open");

        $lexer = new Lexer('[[]]');
    }
    
   
    public function testCharSetOpenError()
    {
        $this->expectException(RegexException::class);        
        $this->expectExceptionMessage("Can't close a character class while none is open");

        
        $lexer = new Lexer(']');
    }
    
    
    public function testCharSetOpenNotClosed()
    {
        $this->expectException(RegexException::class);        
        $this->expectExceptionMessage("Character Class that been closed");

        
        $lexer = new Lexer('[');
    }
    
}
/* End of File */