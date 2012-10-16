<?php
namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;
use Patchwork\Utf8;

/**
  *  Parse a unicode sequence e.g  \x54 \X{4444}
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Unicode implements StrategyInterface
{
    
    /**
      *  Parse the current token for new Quantifiers
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\LiteralScope $head
      *  @param ReverseRegex\Generator\Scope $set
      *  @param ReverseRegex\Lexer $lexer
      */
    public function parse(Scope $head, Scope $set, Lexer $lexer)
    {
        switch(true) {
            case ($lexer->isNextToken(Lexer::T_SHORT_P)) :
                throw new ParserException('Property \p (Unicode Property) not supported use \x to specify unicode character or range');
            break;
            case ($lexer->isNextToken(Lexer::T_SHORT_UNICODE_X)) :
                
                $lexer->moveNext();
                if($lexer->lookahead['value'] !== '{' )  {
                    throw new ParserException('Expecting character { after \X none found');                
                }
                
                $tokens = array();
                while($lexer->moveNext() && $lexer->lookahead['value']  !== '}') {
                    
                    # check if we nested eg.{ddd{d}
                    if($lexer->lookahead['value']  === '{') {
                        throw new ParserException('Nesting hex value ranges is not allowed');
                    }
                
                    if($lexer->lookahead['value'] !== " " && ctype_xdigit($lexer->lookahead['value']) === false) {
                        throw new ParserException(sprintf('Character %s is not a hexdeciaml digit',$lexer->lookahead['value']));
                    }
                
                    $tokens[] = $lexer->lookahead['value'];
                }
                # check that current lookahead is a closing character as it's possible to iterate to end of string (i.e. lookahead === null)
                if($lexer->lookahead['value'] !== '}') {
                    throw new ParserException('Closing quantifier token `}` not found');     
                }
                
                if(count($tokens) === 0) {
                    throw new ParserException('No hex number found inside the range');
                }
                
                $number    = trim(implode('',$tokens));
                $character = Utf8::chr(hexdec($number));
                
                $head->addLiteral($character);
                
            break;
            case ($lexer->isNextToken(Lexer::T_SHORT_X)) :
                // only allow another 2 hex characters
                $glimpse = $lexer->glimpse();
                if($glimpse['value']  === '{') {
                    throw new ParserException('Braces not supported here');
                }
                
                $tokens = array();
                $count = 2;
                while($count > 0 && $lexer->moveNext()) {
                    $tokens[] = $lexer->lookahead['value'];
                    --$count;
                }
                
                $value     = trim(implode('',$tokens));
                $character = Utf8::chr(hexdec($value));
                $head->addLiteral($character);
                
            break;
            default :
                //do nothing no token matches found
        }
        
        return $head;
        
    }
    
    
    
    
    
}
/* End of File */