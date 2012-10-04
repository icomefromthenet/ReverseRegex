<?php
namespace ReverseRegex\Generator;


use ReverseRegex\Generator\Scope;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use ReverseRegex\Exception as GeneratorException;

/**
  *  Scope for Alternating Values
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class AlternationScope extends Scope
{
    /**
      *  Generate a text string appending to result arguments
      *
      *  @access public
      *  @param string $result
      *  @param GeneratorInterface $generator
      */
    public function generate(&$result,GeneratorInterface $generator)
    {
        if($this->count() < 2) {
            throw new GeneratorException('There are no values to alternate over or only a single value');
        }
        
       $repeat_x = $this->calculateRepeatQuota($generator);
        
        while($repeat_x > 0) {
            
            # pick a random child 
            $random_child_index = \round($generator->generate(0,($this->count()-1)));
            
            # seek the internal iterator to this random child
            $this->rewind();
            while($random_child_index > 0) {
                $this->next();
                --$random_child_index;
            }
            
            # call generate on that scope
            $this->current()->generate($result,$generator);
            
            # de-increment the repeat counter
             --$repeat_x;
        }
        
        return $result;
        
    }

}
/* End of File */