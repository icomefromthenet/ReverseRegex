<?php
namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

/**
  *  Parse a following Shorts (\d, \w, \D, \W, \s, \S, dot)
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Short implements StrategyInterface
{
    
    
    /**
      *  Parse the current token for Short Codes `.` `\d`  `\w`
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $set
      *  @param ReverseRegex\Lexer $lexer
      */
    public function parse(Scope $head, Scope $set, Lexer $lexer)
    {
        switch(true) {
            case ($lexer->isNextToken(Lexer::T_DOT)) :
                $this->convertDotToRange($head,$set,$lexer);
            break;
            case ($lexer->isNextToken(Lexer::T_SHORT_D)) :
            case ($lexer->isNextToken(Lexer::T_SHORT_NOT_D)) :
                $this->convertDigitToRange($head,$set,$lexer);
            break;
            case ($lexer->isNextToken(Lexer::T_SHORT_W)) :
            case ($lexer->isNextToken(Lexer::T_SHORT_NOT_W)) :
                $this->convertWordToRange($head,$set,$lexer);
            break;
            case ($lexer->isNextToken(Lexer::T_SHORT_S)) :
            case ($lexer->isNextToken(Lexer::T_SHORT_NOT_S)) :
                $this->convertWhiteSpaceToRange($head,$set,$lexer);
            break;
            default :
                //do nothing no token matches found
        }
        
    }
    
    
    public function convertDotToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        for($i = 0; $i <= 127; $i++) {
            $head->addLiteral(chr($i));
        }
    }
    
    public function convertDigitToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        if($lexer->isNextToken(Lexer::T_SHORT_D)) {
            # digits only (0048 - 0057) digits      
            $min = 48;
            $max = 57;
            while($min <= $max) {
                $head->addLiteral(chr($min));
                $min++;
            }
        }
        else {
            
            # not digits every assci character expect (0048 - 0057) digits
            $min = 0;
            $max = 47;
            while($min <= $max) {
                $head->addLiteral(chr($min));
                $min++;
            }
            
            $min = 58;
            $max = 127;
            while($min <= $max) {
                $head->addLiteral(chr($min));
                $min++;
            }

        }
                
    }
    
    
    public function convertWordToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        if($lexer->isNextToken(Lexer::T_SHORT_W)) {
            # `[a-zA-Z0-9_]`
            
            # 48 - 57
            for($i = 48; $i <= 57; $i++) {
                $head->addLiteral(chr($i));
            }
            
            # 65 - 90
            for($i = 65; $i <= 90; $i++) {
                $head->addLiteral(chr($i));
            }
            
            # 95
            $head->addLiteral(chr(95));
            
            # 97 - 122
            for($i = 97; $i <= 122; $i++) {
                $head->addLiteral(chr($i));
            }
            
        } else {
           # `![a-zA-Z0-9_]`    
           
           # 0 - 47
           for($i = 0; $i <= 47; $i++) {
                $head->addLiteral(chr($i));
           }
           
           # 58 - 64
           for($i = 58; $i <= 64; $i++) {
                $head->addLiteral(chr($i));
           }
           
           # 91 - 94
           for($i = 91; $i <= 94; $i++) {
                $head->addLiteral(chr($i));
           }
            
           # 96   
           $head->addLiteral(chr(96));
           
           # 123 - 127
           for($i = 123; $i <= 127; $i++) {
               $head->addLiteral(chr($i));
           }
           
        }
    }
    
    public function convertWhiteSpaceToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        if($lexer->isNextToken(Lexer::T_SHORT_S)) {
            # spaces, tabs, and line breaks
            #0009 #0010 #0012 #0013 #0032
            
            $head->addLiteral(chr(9));
            $head->addLiteral(chr(10));
            $head->addLiteral(chr(12));
            $head->addLiteral(chr(13));
            $head->addLiteral(chr(32));
        
        } else {
            # not spaces, tabs, and line breaks
            #0000-0008  #0011  #0014 - #0031
            
            for($i=0; $i <= 8; $i++) {
                $head->addLiteral(chr($i));
            }
            
            $head->addLiteral(chr(11));
            
            for($i=14; $i <= 31; $i++) {
                $head->addLiteral(chr($i));
            }
        }
    }
    
}
/* End of File */