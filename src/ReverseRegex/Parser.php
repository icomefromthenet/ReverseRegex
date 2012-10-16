<?php
namespace ReverseRegex;


use ReverseRegex\Exception as ParserException;
use ReverseRegex\Lexer;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Generator\LiteralScope;

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
      *  @var ReverseRegex\Generator\Scope the current head
      */
    protected $head;
    
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param Lexer $lexer
      */
    public function __construct(Lexer $lexer, Scope $result, Scope $head = null)
    {
        $this->lexer  = $lexer;
        $this->result = $result;
        
        if($head === null) {
            $this->head = new Scope();
        } else {
            $this->head  = $head;    
        }
        
        $this->result->attach($head);
        
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
    public function parse($sub = false)
    {
        
        try {
        
            while($this->lexer->moveNext()) {
                
                $result = null;
                $scope  = null;
                $parser = null;
                
                switch(true) {
                    case($this->lexer->isNextToken(Lexer::T_GROUP_OPEN)) :
                        
                        # is the group character the first token? is the regex wrapped in brackets. 
                        if($this->lexer->token !== null) {
                            continue;
                        }
                        
                        # not this is a new group create new parser instance
                        $parser = new Parser($this->lexer,new Scope(),new Scope());
                        $this->head->attach($parser->parse(true)->getResult());  
                        
                    
                    break;
                    case($this->lexer->isNextToken(Lexer::T_GROUP_CLOSE)) :
                        
                        # group is finished don't want to contine this loop
                        break;
                    break;
                    case ($this->lexer->isNextTokenAny(array(Lexer::T_LITERAL_CHAR,Lexer::T_LITERAL_NUMERIC))):    
                        
                        # test for literal characters (abcd)
                        $scope = new LiteralScope();
                        $scope->addLiteral($this->lexer->lookahead['value']);
                        $this->head->attach($scope);
                    break;
                    case($this->lexer->isNextToken(Lexer::T_SET_OPEN)) :
                        
                        # character classes [a-z]
                        $scope  = new LiteralScope();
                        $parser = self::createSubParser('character')->parse($scope,$this->head,$lexer);
                        $this->head->attach($scope);
                            
                    break;
                    case ($this->lexer->isNextTokenAny(array(
                                                             Lexer::T_DOT,
                                                             Lexer::T_SHORT_D,
                                                             Lexer::T_SHORT_NOT_D,
                                                             Lexer::T_SHORT_W,
                                                             Lexer::T_SHORT_NOT_W,
                                                             Lexer::T_SHORT_S,
                                                             Lexer::T_SHORT_NOT_S))):
                        
                        # match short (. \d \D \w \W \s \S)
                        $scope  = new LiteralScope();
                        $parser = self::createSubParser('short')->parse($scope,$this->head,$lexer);
                        $this->head->attach($scope);
                        
                    break;
                    case ($this->lexer->isNextTokenAny(array(
                                                             Lexer::T_SHORT_P,
                                                             Lexer::T_SHORT_UNICODE_X,
                                                             Lexer::T_SHORT_X))):
                        
                        # match short (\p{L} \x \X  )
                        $scope  = new LiteralScope();
                        $parser = self::createSubParser('unicode')->parse($scope,$this->head,$lexer);
                        $this->head->attach($scope);
                        
                    break;
                    case ($this->lexer->isNextTokenAny(array(
                                                             Lexer::T_QUANTIFIER_OPEN,
                                                             Lexer::T_QUANTIFIER_PLUS,
                                                             Lexer::T_QUANTIFIER_QUESTION,
                                                             Lexer::T_QUANTIFIER_STAR,
                                                             Lexer::T_QUANTIFIER_OPEN
                                                             ))):
                        
                        # match quantifiers 
                        $parser = self::createSubParser('quantifer')->parse($this->head,$this->head,$lexer);
                        $this->head->attach($scope);
                        
                    break;
                    case ($this->lexer->isNextToken(Lexer::T_CHOICE_BAR)):
                        
                        # match alternations
                        $this->head = new Scope();
                        $this->result->attach($this->head); 
                        
                    break;    
                    default:
                        throw new ParserException('No Parse Action Taken');
                }    
                
                
            }
        
        }
        catch(ParserException $e)
        {
            throw $e;
        }
    
        return $this;        
    }
    
    /**
      *  Return the result of the parse 
      */
    public function getResult()
    {
        return $this->result;
    }
    
    
    public static $sub_parsers = array(
      'character'  => '\\ReverseRegex\\Parser\\CharacterClass', 
       'unicode'   => '\\ReverseRegex\\Parser\\Unicode',
       'quantifer' => '\\ReverseRegex\\Parser\\Quantifier',
       'short'     => '\\ReverseRegex\\Parser\\Short'
    );
    
    /**
      *  Return an instance os subparser
      *
      *  @access public
      *  @static
      *  @return ReverseRegex\Parser\StrategyInterface
      *  @param string $name the short name 
      */    
    static function createSubParser($name)
    {
        
        if(isset(self::$sub_parsers[$name]) === false) {
            throw new ParserException('Unknown subparser at '.$name);
        }
        
        if(is_object(self::$sub_parsers[$name]) === false) {
            self::$sub_parsers[$name] = new self::$sub_parsers[$name]();
        }
        
        return self::$sub_parsers[$name];
    }
    
}
/* End of File */