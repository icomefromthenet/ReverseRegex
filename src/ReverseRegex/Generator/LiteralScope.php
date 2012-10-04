<?php

namespace ReverseRegex\Generator;


use ReverseRegex\Generator\Scope;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
      *  @var Doctrine\Common\Collections\ArrayCollection container for literals
      */
    protected $literals;

    /**
      *  Class Constructor
      *
      *  @access public
      *  @param string $label
      *  @param Node $parent
      */
    public function __construct($label = self::TEMPLATE_LABEL)
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
      *  Generate a text string appending to result arguments
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
             $randomIndex = \round($generator->generate(0,($this->literals->count()-1)));        
             $result     .= $this->literals->get($randomIndex);
             
             --$repeat_x;
        }
        
        return $result;
        
    }

}
/* End of File */