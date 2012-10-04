<?php
namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Exception as ParserException;

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
        
        # move over the current quantifier token
        $lexer->moveNext();        
        
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
        
        # move over the current quantifier token
        $lexer->moveNext(); 
        
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
        
        # move over the current quantifier token
        $lexer->moveNext(); 
        
        return $head;
    }
    
    /**
      *  Parse the current token for closers : {###} {##,} {##,##}
      *
      *  @access public
      *  @return ReverseRegex\Generator\Scope a new head
      *  @param ReverseRegex\Generator\Scope $head
      *  @param ReverseRegex\Generator\Scope $result
      *  @param ReverseRegex\Lexer $lexer
      */
    public function quantifyClosure(Scope $head, Scope $result, Lexer $lexer)
    {
        # check format a {#####}
        while($lexer->peek())
        
        
        
        
        
        return $head;
    }
}
/* End of File */