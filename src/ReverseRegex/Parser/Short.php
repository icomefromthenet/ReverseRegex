<?php
namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Generator\LiteralScope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

class Short implements StrategyInterface
{
    
    
    /**
      *  Parse the current token for Short Codes `.` `\d`  `\w`
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $result
      *  @param ReverseRegex\Lexer $lexer
      */
    public function parse(Scope $head, Scope $result, Lexer $lexer)
    {
        switch(true) {
            case ($lexer->lookahead['type'] === Lexer::T_DOT) :
                $head = $this->convertDotToRange($head,$result,$lexer);
            break;
            case ($lexer->lookahead['type'] === Lexer::T_SHORT_D) :
            case ($lexer->lookahead['type'] === Lexer::T_SHORT_NOT_D) :
                $head = $this->convertDigitToRange($head,$result,$lexer);
            break;
            case ($lexer->lookahead['type'] === Lexer::T_SHORT_W) :
            case ($lexer->lookahead['type'] === Lexer::T_SHORT_NOT_W) :
                $head = $this->convertWordToRange($head,$result,$lexer);
            case ($lexer->lookahead['type'] === Lexer::T_SHORT_S) :
            case ($lexer->lookahead['type'] === Lexer::T_SHORT_NOT_S) :
                $head = $this->convertWhiteSpaceToRange($head,$result,$lexer);
            break;
            default :
                //do nothing no token matches found
        }
        
        return $head;
        
    }
    
    
    public function convertDotToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        $min = 32;
        $max = 127;
        $scope   = new LiteralScope();
        
        # digits only        
        while($min !== $max) {
            $scope->addLiteral(chr($min));
            $min++;
        }
        
        
    }
    
    public function convertDigitToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        $numbers = range(0,9);
        $scope   = new LiteralScope();
        
        if($lexer->lookahead['type'] === Lexer::T_SHORT_D) {
            # digits only        
            foreach($numbers as $number) {
                $scope->addLiteral($number);
            }
        }
        else {
            # not digits
            //32 47
            $min = 32;
            $max = 47;

            while($min !== $max) {
                $scope->addLiteral(chr($min));
                $min++;
            }
            
            //58 //126
            $min = 58;
            $max = 126;

            while($min !== $max) {
                $scope->addLiteral(chr($min));
                $min++;
            }

        }
                
    }
    
    
    public function convertWordToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        
                
    }
    
    public function convertWhiteSpaceToRange(Scope $head, Scope $result, Lexer $lexer)
    {
        
    }
    
}
/* End of File */