<?php
namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

/**
  *  Parse a group quantifer e.g (abghb){1,5} , (abghb){5} , (abghb)* , (abghb)? , (abghb)+
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Quantifier implements StrategyInterface
{
    
    /**
      *  Parse the current token for new Quantifiers
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
            case ($lexer->lookahead['type'] === Lexer::T_QUANTIFIER_PLUS) :
                $head = $this->quantifyPlus($head,$result,$lexer);
            break;
            case ($lexer->lookahead['type'] === Lexer::T_QUANTIFIER_QUESTION) :
                $head = $this->quantifyQuestion($head,$result,$lexer);
            break;
            case ($lexer->lookahead['type'] === Lexer::T_QUANTIFIER_STAR) :
                $head = $this->quantifyStar($head,$result,$lexer);
            break;
             case ($lexer->lookahead['type'] === Lexer::T_QUANTIFIER_OPEN) :
                $head = $this->quantifyClosure($head,$result,$lexer);
            break;
            default :
                //do nothing no token matches found
        }
        
        return $head;
        
    }
    
    
    /**
      *  Parse the current token for + quantifiers
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $result
      *  @param ReverseRegex\Lexer $lexer
      */
    public function quantifyPlus(Scope $head, Scope $result, Lexer $lexer)
    {
        $min = 1;
        $max = PHP_INT_MAX;
        
        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);
        
        return $head;
    }
    
    /**
      *  Parse the current token for * quantifiers
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $result
      *  @param ReverseRegex\Lexer $lexer
      */
    public function quantifyStar(Scope $head, Scope $result, Lexer $lexer)
    {
        $min = 0;
        $max = PHP_INT_MAX;
        
        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);
        
        return $head;
    }
    
    /**
      *  Parse the current token for ? quantifiers
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $result
      *  @param ReverseRegex\Lexer $lexer
      */
    public function quantifyQuestion(Scope $head, Scope $result, Lexer $lexer)
    {
        $min = 0;
        $max = 1;
        
        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);
        
        return $head;
    }
    
    
    /**
      *  Parse the current token for closers : {###} { ## } {##,##}
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $result
      *  @param ReverseRegex\Lexer $lexer
      */
    public function quantifyClosure(Scope $head, Scope $result, Lexer $lexer)
    {
        $tokens = array();
        $min = $head->getMinOccurances();
        $max = $head->getMaxOccurances();
        
        # move to the first token inside the quantifer.
        $lexer->moveNext();
        
        # parse for the minimum , move lookahead until read end of the closure or the `,`
        while($lexer->lookahead !== null && $lexer->lookahead['type']  !== Lexer::T_QUANTIFIER_CLOSE && $lexer->lookahead['value'] !== ',' ) {

            if($lexer->lookahead['type']  === Lexer::T_QUANTIFIER_OPEN) {
                throw new ParserException('Nesting Quantifiers is not allowed');
            }
            $tokens[] = $lexer->lookahead;
            $lexer->moveNext();   
        }
        
        $min = $this->convertInteger($tokens);
        
        # do we have a maximum after the comma?
        if($lexer->lookahead['value'] === ',' ) {
        
            # make sure we have values to gather ie not {778,}
            $tokens = array();
            
             # move to the first token after the `,` character 
            $lexer->moveNext();
            
            # grab the remaining numbers
            while($lexer->lookahead !== null && $lexer->lookahead['type']  !== Lexer::T_QUANTIFIER_CLOSE) {
                
                if($lexer->lookahead['type']  === Lexer::T_QUANTIFIER_OPEN) {
                    throw new ParserException('Nesting Quantifiers is not allowed');
                }
                
                $tokens[] = $lexer->lookahead;
                $lexer->moveNext();   
            }
            
            $max = $this->convertInteger($tokens);
            
        }
        else {
            $max = $min;
        }
        
        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);
        
        # skip the lexer to the closing token
        $lexer->skipUntil(Lexer::T_QUANTIFIER_CLOSE);
        
        # check if the last matched token was the closing bracket
        # not going to stop errors like {#####,###{[a-z]} {#####{[a-z]}
        if($lexer->lookahead['type'] !== Lexer::T_QUANTIFIER_CLOSE) {
            throw new ParserException('Closing quantifier token `}` not found');     
        }
        
        return $head;
    }
    
    
    /**
      *  Convert a collection of Lexer::T_LITERAL_NUMERIC tokens into integer
      *
      *  @access public
      *  @return integer the size
      *  @param array $tokens collection of tokens from lexer
      */
    protected function convertInteger(array $tokens)
    {
        $number_string = array_map(function($item) { return $item['value']; }, $tokens);
        $number_string = trim(implode('',$number_string));
        
        $value = preg_match('/^(0|(-{0,1}[1-9]\d*))$/', $number_string);

        if ($value == 0) {
            throw new ParserException('Quantifier expects and integer compitable string');
        }
        
        return intval($number_string);
    }
    
}
/* End of File */