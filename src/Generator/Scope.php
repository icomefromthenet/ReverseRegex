<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use ReverseRegex\Exception as GeneratorException;
use ReverseRegex\Random\GeneratorInterface;

/**
 *  Base Class for Scopes, which are nodes in the generator tree.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *  @since 0.0.1
 */
class Scope extends Node implements ContextInterface, RepeatInterface, AlternateInterface
{
    private int $minOccurances = 1;

    private int $maxOccurances = 1;

    private bool $useAlternatingStrategy = false;

    public function __construct(?string $label = null)
    {
        parent::__construct($label);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(?string &$result, GeneratorInterface $generator): void
    {
        if ($this->isEmpty()) {
            // Revert message to match original test's expectation
            throw new GeneratorException('No child scopes to call must be atleast 1');
        }

        $repeatCount = $this->calculateRepeatQuota($generator);
        $children = $this->getChildren()->getValues();
        $childCount = count($children);

        for ($i = 0; $i < $repeatCount; $i++) {
            if ($this->useAlternatingStrategy) {
                // Alternation (e.g., a|b|c): pick one child randomly and generate it.
                $randomIndex = $generator->generate(0, $childCount - 1);
                $children[$randomIndex]->generate($result, $generator);
            } else {
                // Concatenation (e.g., abc): generate all children in order.
                foreach ($children as $child) {
                    $child->generate($result, $generator);
                }
            }
        }
    }

    /**
     * Restore original 1-indexed public get() method for test compatibility.
     */
    public function get(int $index): ?Node
    {
        // Emulate original 1-based index
        if ($index <= 0) {
            return null;
        }

        return $this->getChildren()->get($index - 1);
    }

    /**
     * Restore original rewind() method for test compatibility.
     * This makes Scope behave like the old custom iterator.
     */
    public function rewind(): self
    {
        $this->getIterator()->rewind();
        return $this;
    }

    /**
     * Restore original current() method for test compatibility.
     */
    public function current(): ?Node
    {
        /** @var Node|false $current */
        $current = $this->getIterator()->current();
        return $current === false ? null : $current;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxOccurances(): int
    {
        return $this->maxOccurances;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxOccurances(int $num): void
    {
        $this->maxOccurances = $num;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinOccurances(): int
    {
        return $this->minOccurances;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinOccurances(int $num): void
    {
        $this->minOccurances = $num;
    }

    /**
     * {@inheritdoc}
     */
    public function getOccuranceRange(): int
    {
        return $this->maxOccurances - $this->minOccurances;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateRepeatQuota(GeneratorInterface $generator): int
    {
        if ($this->getOccuranceRange() > 0) {
            return $generator->generate($this->minOccurances, $this->maxOccurances);
        }
        return $this->minOccurances;
    }

    /**
     * {@inheritdoc}
     * Restore original signature for test compatibility
     */
    public function useAlternatingStrategy(bool $use = true): void
    {
        $this->useAlternatingStrategy = $use;
    }

    /**
     * {@inheritdoc}
     */
    public function usingAlternatingStrategy(): bool
    {
        return $this->useAlternatingStrategy;
    }
}
