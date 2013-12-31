<?php
namespace ReverseRegex\Generator;


use PHPStats\Generator\GeneratorInterface;

/**
  *  Conext interface for Generator
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface ContextInterface
{
    /**
      *  Generate a text string appending to result arguments
      *
      *  @access public
      *  @param string $result
      *  @param GeneratorInterface $generator
      */
    public function generate(&$result,GeneratorInterface $generator);
    
}

/* End of File */