<?php
namespace ReverseRegex;

use Doctrine\Common\Collections\ArrayCollection as BaseCollection;

class ArrayCollection extends BaseCollection
{
    
    /**
      *  Sort the values using a ksort
      *
      *  @access public
      *  @return ArrayCollection
      */
    public function sort()
    {
        $values = $this->toArray();
        ksort($values);
        
        $this->clear();
        
        foreach($values as $index => $value) {
            $this->set($index,$value);
        }
        
        return $this;
    }
    
    
}
/* End of Class */


