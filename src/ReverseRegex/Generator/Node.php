<?php
namespace ReverseRegex\Generator;

use \ArrayObject;
use \SplObjectStorage;
use \ArrayAccess;
use \Countable;
use \Iterator;

/**
  *  Base to all Generator Scopes 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Node implements ArrayAccess, Countable, Iterator
{
    /**
      *  @var string name of the node 
      */
    protected $label;
    
    /**
      *  @var ArrayObject container for node metadata 
      */
    protected $attrs;
    
    /**
      *  @var SplObjectStorage container for node relationships 
      */
    protected $links;

    /**
      *  Class Constructor
      *
      *  @access public
      *  @param string $label
      */
    public function __construct($label = 'node')
    {
        $this->attrs = new ArrayObject();
        $this->links = new SplObjectStorage();

        $this->setLabel($label);
    }

    /**
      *  Fetch the nodes label
      *
      *  @access public
      *  @return string the nodes label
      */
    public function getLabel()
    {
        return $this->label;
    }

    /**
      *  Sets the node label
      *
      *  @access public
      *  @param string $label the nodes label
      */
    public function setLabel($label)
    {
        if (!(is_scalar($label) || is_null($label))) {
            return false;
        }

        $this->label = $label;
    }


    /**
      *  Attach a node
      *
      *  @access public
      *  @param Node $node the node to attach
      *  @return Node
      */
    public function &attach(Node $node)
    {
        $this->links->attach($node);

        return $this;
    }

    /**
      *  Detach a node
      *
      *  @access public
      *  @return Node
      *  @param Node $node the node to remove
      */
    public function &detach(Node $node)
    {
        foreach ($this->links as $linked_node) {
            if ($linked_node == $node) {
                $this->links->detach($node);
            }
        }

        return $this;
    }

    /**
      *  Search for node in its relations
      *
      *  @access public
      *  @return boolean true if found
      *  @param Node $node the node to search for
      */
    public function contains(Node $node)
    {
        foreach ($this->links as $linked_node) {
            if ($linked_node == $node) {
                return true;
            }
        }

        return false;
    }
  
   /**
     *  Apply a closure to all relations
     *
     *  @access public
     *  @param Closer the function to apply
     */
    public function map(Closure $function)
    {
        foreach ($this->links as $node) {
            $function($node);
        }
    }
    
    //------------------------------------------------------------------
    # Countable
    
    public function count()
    {
        return count($this->links);
    }
    
    //------------------------------------------------------------------
    # Iterator

    public function current()
    {
        return $this->links->current();
    }
    public function key()
    {
        return $this->links->key();
    }
    public function next()
    {
        return $this->links->next();
    }
    public function rewind()
    {
        return $this->links->rewind();
    }
    public function valid()
    {
        return $this->links->valid();
    }
    
    //------------------------------------------------------------------
    # ArrayAccess Implementation

    public function offsetGet($key)
    {
        return $this->attrs->offsetGet($key);
    }

    public function offsetSet($key, $value)
    {
        $this->attrs->offsetSet($key, $value);
    }

    public function offsetExists($key)
    {
        return $this->attrs->offsetExists($key);
    }

    public function offsetUnset($key)
    {
        return $this->attrs->offsetUnset($key);
    }
}

/* End of Class */