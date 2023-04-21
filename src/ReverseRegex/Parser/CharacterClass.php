<?php
namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

/**
  *  Parse a character class [0-9][a-z]
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class CharacterClass implements StrategyInterface
{
    
    
    /**
      *  Will return a normalized ie unicode sequences been evaluated.
      *
      *  @return string a normalized character class string
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $set
      *  @param Lexer $lexer the lexer to normalize
      */
    public function normalize(Scope $head, Scope $set, Lexer $lexer)
    {
        $collection = array();
        $unicode = new Unicode();
        
        while($lexer->moveNext() && !$lexer->isNextToken(Lexer::T_SET_CLOSE)) {
            
            $value = null;
            
            switch(true) {
                case($lexer->isNextTokenAny(array(Lexer::T_SHORT_UNICODE_X, Lexer::T_SHORT_P,Lexer::T_SHORT_X))):
                    $collection[] = $unicode->evaluate($lexer);
                break;
                case($lexer->isNextTokenAny(array(Lexer::T_LITERAL_CHAR, Lexer::T_LITERAL_NUMERIC))):
                    $collection[] = $lexer->lookahead['value'];
                break;
                case($lexer->isNextToken(Lexer::T_SET_RANGE)):
                    $collection[] = '-';
                break;
                case($lexer->isNextToken(Lexer::T_ESCAPE_CHAR)):
                    $collection[] = '\\';
                break;
                default :
                  throw new ParserException('Illegal meta character detected in character class');
            }
            
        }
        
        /*
        if($lexer->lookahead['type'] === null) {
            throw new ParserException('Closing character set token not found');
        } */
        
        return '['. implode('',$collection) .']';
        
    }
    
    
    
    /**
      *  Parse the current token for new Quantifiers
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $set
      *  @param ReverseRegex\Lexer $lexer
      */
    public function parse(Scope $head, Scope $set, Lexer $lexer)
    {
        if($lexer->lookahead['type'] !== Lexer::T_SET_OPEN) {
            throw new ParserException('Opening character set token not found');
        }
        
        $peek = $lexer->glimpse();
        if($peek['type'] === Lexer::T_SET_NEGATED) {
             throw new ParserException('Negated Character Set ranges not supported at this time');
        }
        
        $normal_lexer = new Lexer($this->normalize($head,$set,$lexer));
        
        while( $normal_lexer->moveNext() && !$normal_lexer->isNextToken(Lexer::T_SET_CLOSE)) {
            $glimpse = $normal_lexer->glimpse();
         
            if($glimpse['type'] === Lexer::T_SET_RANGE) {
                continue; //value be included in range when `-` character is passed
            }
         
            switch(true) {
                case($normal_lexer->isNextToken(Lexer::T_SET_RANGE)) :
                    $range_start = $normal_lexer->token['value'];
                    
                    $normal_lexer->moveNext();
                    
                    if($normal_lexer->isNextToken(Lexer::T_ESCAPE_CHAR)) {
                        $normal_lexer->moveNext();
                    }
                    
                    $range_end =  $normal_lexer->lookahead['value'];
                    $this->fillRange($head,$range_start,$range_end);
                    
                break;    
                case($normal_lexer->isNextToken(Lexer::T_LITERAL_NUMERIC) || $normal_lexer->isNextToken(Lexer::T_LITERAL_CHAR)) :
                    $index = (integer)mb_ord($normal_lexer->lookahead['value']);
                    $head->setLiteral($index,$normal_lexer->lookahead['value']);
                break;
                default:
                    # ignore     
            }
        }
        
        
        $head->getLiterals()->sort();
        
        return $head;
    }
    
    /**
      *  Fill a range given starting and ending character
      *
      *  @return void
      *  @access public
      */
    public function fillRange(Scope $head,$start,$end)
    {
        
        $start_index  = mb_ord($start);
        $ending_index = mb_ord($end);
        
        if($ending_index < $start_index ) {
            throw new ParserException(sprintf('Character class range %s - %s is out of order',$start,$end));
        }
        
        for($i = $start_index; $i <= $ending_index; $i++) {
            $head->setLiteral($i, mb_chr($i));
        }    
    }
  
   
    
}


/* End of File */