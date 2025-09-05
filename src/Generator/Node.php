<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use Doctrine\Common\Collections\ArrayCollection;
use Traversable;
use IteratorAggregate;
use Countable;
use ReverseRegex\Random\GeneratorInterface;

/**
 *  A node in the generator graph, representing a component of the regular expression.
 *  Implements IteratorAggregate to allow direct iteration over its children.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *  @since 0.0.1
 *  @implements IteratorAggregate<int, Node>
 */
abstract class Node implements IteratorAggregate, Countable
{
    /** @var ArrayCollection<int, Node> The collection of child nodes. */
    private ArrayCollection $children;

    /**
     * Generate a string segment based on the node's rules and children.
     * This method must be implemented by all concrete node types.
     */
    abstract public function generate(string &$result, GeneratorInterface $generator): void;

    public function __construct(private ?string $label = null)
    {
        $this->children = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     * @return \Iterator<int, Node>
     */
    public function getIterator(): Traversable
    {
        return $this->children->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->children->count();
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Attach a child node.
     *
     * @param Node $node The node to attach as a child.
     * @return $this
     */
    public function attach(Node $node): self
    {
        $this->children->add($node);
        return $this;
    }

    /**
     * Detach a child node.
     *
     * @param Node $node The node to remove from the children.
     * @return $this
     */
    public function detach(Node $node): self
    {
        $this->children->removeElement($node);
        return $this;
    }

    /**
     * Check if a node is a direct child of this node.
     *
     * @param Node $node The node to search for.
     * @return bool True if the node is found.
     */
    public function contains(Node $node): bool
    {
        return $this->children->contains($node);
    }

    /**
     * Checks if this node has any children.
     */
    public function isEmpty(): bool
    {
        return $this->children->isEmpty();
    }

    /**
     * Gets the collection of child nodes.
     *
     * @return ArrayCollection<int, Node>
     */
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }
}
