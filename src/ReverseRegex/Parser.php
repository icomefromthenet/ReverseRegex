<?php
namespace ReverseRegex;


use ReverseRegex\Exception as ParserException;
use ReverseRegex\Lexer;
use ReverseRegex\Generator\Scope;

/**
  *  Parser to convert regex into Group
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Parser
{
    /**
      *  @var Lexer 
      */
    protected $lexer;
    
    /**
      *  @var  ReverseRegex\Generator\Scope
      */
    protected $result;
    
    /**
      *  @var ReverseRegex\Generator\Scope  the current head
      */
    protected $head;
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param Lexer $lexer
      */
    public function __construct(Lexer $lexer, Scope $result)
    {
        $this->lexer  = $lexer;
        $this->result = $result;
    }
    
    
    /**
      *  Fetch the regex lexer
      *
      *  @access public
      *  @return Lexer
      */
    public function getLexer()
    {
        return $this->lexer;
    }
    
    
    /**
      *  Will parse the regex into generator
      *
      *  @access public
      *  @return 
      */
    public function parse()
    {
        
        
        
        while($this->lexer->moveNext()) {
            
            switch(true) {
            
            # test for opening group tag
            case($this->lexer->lookahead['type'] === Lexer::T_GROUP_OPEN) :
            
            
            break;
            # test for literal characters
            case ($this->lexer->lookahead['type'] === Lexer::T_LITERAL_CHAR):    
                
            break;
        
            # test for literal numbers
            case ($this->lexer->lookahead['type'] === Lexer::T_LITERAL_NUMERIC):
                
            break;    
            default:
                throw new ParserException('No Parse Action Taken');
            }    
            
            
        }
     
     
        return $result;
    }
    
    /**
      *  Return the result of the parse 
      */
    public function result()
    {
        
    }
    
    /**
      *  Flush the result for another parse 
      */
    public function flush()
    {
        
        
    }
    
}
/* End of File */