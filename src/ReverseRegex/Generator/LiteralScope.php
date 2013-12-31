<?php
namespace ReverseRegex\Generator;

use ReverseRegex\Generator\Scope;
use PHPStats\Generator\GeneratorInterface;
use ReverseRegex\ArrayCollection;
use ReverseRegex\Exception as GeneratorException;

/**
  *  Scope for Literal Values
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class LiteralScope extends Scope
{
    /**
      *  @var ReverseRegex\ArrayCollection container for literals values
      */
    protected $literals;

    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param string $label
      *  @param Node $parent
      */
    public function __construct($label = 'label')
    {
        parent::__construct($label);
        
        $this->literals = new ArrayCollection();
    }

    /**
      *  Adds a literal value to internal collection
      *
      *  @access public
      *  @param mixed $literal
      */
    public function addLiteral($literal)
    {
        $this->literals->add($literal);
    }

    /**
      *  Sets a value on the internal collection using a key
      *
      *  @access public
      *  @param string $hex a hexidecimal number
      *  @param string $literal the literal to store
      */
    public function setLiteral($hex,$literal)
    {
        $this->literals->set($hex,$literal);
    }
    
    /**
      *  Return the literal ArrayCollection
      *
      *  @access public
      *  @return Doctrine\Common\Collections\ArrayCollection
      */
    public function getLiterals()
    {
        return $this->literals;
    }
    
    
    /**
      *  Generate a text string appending to the result argument
      *
      *  @access public
      *  @param string $result
      *  @param GeneratorInterface $generator
      */
    public function generate(&$result,GeneratorInterface $generator)
    {
        if($this->literals->count() === 0) {
            throw new GeneratorException('There are no literals to choose from');
        }
        
       $repeat_x = $this->calculateRepeatQuota($generator);
        
        while($repeat_x > 0) {
             $randomIndex = 0;
             
             if($this->literals->count() > 1) {
                $randomIndex = \round($generator->generate(1,($this->literals->count())));        
             }
             
             $result     .= $this->literals->getAt($randomIndex);
             
             --$repeat_x;
        }
        
        return $result;
        
    }

}
/* End of File */