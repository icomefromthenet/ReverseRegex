<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use ArrayAccess;
use ArrayObject;
use Closure;
use Countable;
use Iterator;
use SplObjectStorage;

/**
 *  Base to all Generator Scopes.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 *
 * @implements ArrayAccess<array-key, mixed>
 * @implements Iterator<int, Node>
 */
class Node implements ArrayAccess, Countable, Iterator
{
    /**
     * @var scalar|null name of the node
     */
    protected $label;

    /**
     * @var ArrayObject<array-key, mixed> container for node metadata
     */
    protected $attrs;

    /**
     * @var SplObjectStorage<Node, mixed> container for node relationships
     */
    protected $links;

    /**
     *  Class Constructor.
     *
     * @param mixed $label
     */
    public function __construct($label = 'node')
    {
        $this->attrs = new ArrayObject();
        $this->links = new SplObjectStorage();

        $this->setLabel($label);
    }

    /**
     *  Fetch the nodes label.
     *
     * @return scalar|null the nodes label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     *  Sets the node label.
     *
     * @param mixed $label the nodes label
     *
     * @return bool|null false when the label is not scalar
     */
    public function setLabel($label)
    {
        if (! (\is_scalar($label) || null === $label)) {
            return false;
        }

        $this->label = $label;

        return null;
    }

    /**
     *  Attach a node.
     *
     * @param Node $node the node to attach
     *
     * @return Node
     */
    public function &attach(self $node)
    {
        $this->links->offsetSet($node);

        return $this;
    }

    /**
     *  Detach a node.
     *
     * @param Node $node the node to remove
     *
     * @return Node
     */
    public function &detach(self $node)
    {
        foreach ($this->links as $linked_node) {
            if ($linked_node == $node) {
                $this->links->offsetUnset($node);
            }
        }

        return $this;
    }

    /**
     *  Search for node in its relations.
     *
     * @param Node $node the node to search for
     *
     * @return bool true if found
     */
    public function contains(self $node): bool
    {
        foreach ($this->links as $linked_node) {
            if ($linked_node == $node) {
                return true;
            }
        }

        return false;
    }

    /**
     *  Apply a closure to all relations.
     *
     * @param Closure $function the function to apply
     */
    public function map(Closure $function): void
    {
        foreach ($this->links as $node) {
            $function($node);
        }
    }

    // ------------------------------------------------------------------
    // Countable

    public function count(): int
    {
        return \count($this->links);
    }

    // ------------------------------------------------------------------
    // Iterator

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->links->current();
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->links->key();
    }

    public function next(): void
    {
        $this->links->next();
    }

    public function rewind(): void
    {
        $this->links->rewind();
    }

    public function valid(): bool
    {
        return $this->links->valid();
    }

    // ------------------------------------------------------------------
    // ArrayAccess Implementation

    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        return $this->attrs->offsetGet($key);
    }

    public function offsetSet($key, $value): void
    {
        $this->attrs->offsetSet($key, $value);
    }

    public function offsetExists($key): bool
    {
        return $this->attrs->offsetExists($key);
    }

    public function offsetUnset($key): void
    {
        $this->attrs->offsetUnset($key);
    }
}

// End of Class
