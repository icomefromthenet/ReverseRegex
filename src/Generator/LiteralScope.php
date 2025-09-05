<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use Doctrine\Common\Collections\ArrayCollection;
use ReverseRegex\Exception as GeneratorException;
use ReverseRegex\Random\GeneratorInterface;

/**
 * A specialized scope that holds a collection of literal characters to choose from during generation.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 0.0.1
 */
class LiteralScope extends Scope
{
    /** @var ArrayCollection<int|string, string> The collection of literal characters. */
    private ArrayCollection $literals;

    public function __construct(?string $label = null)
    {
        parent::__construct($label);
        $this->literals = new ArrayCollection();
    }

    /**
     * Adds a literal character to the internal collection.
     *
     * @param string $literal The character to add.
     */
    public function addLiteral(string $literal): void
    {
        $this->literals->add($literal);
    }

    /**
     * Sets a literal character at a specific key, useful for character ranges.
     *
     * @param int|string $key The character's code point or other identifier.
     * @param string $literal The character to store.
     */
    public function setLiteral(int|string $key, string $literal): void
    {
        $this->literals->set($key, $literal);
    }

    /**
     * Returns the internal collection of literals.
     *
     * @return ArrayCollection<int|string, string>
     */
    public function getLiterals(): ArrayCollection
    {
        return $this->literals;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(?string &$result, GeneratorInterface $generator): void
    {
        if ($this->literals->isEmpty()) {
            throw new GeneratorException('There are no literals to choose from in this scope');
        }

        $repeatCount = $this->calculateRepeatQuota($generator);
        $values = $this->literals->getValues();
        $maxIndex = count($values) - 1;

        for ($i = 0; $i < $repeatCount; $i++) {
            if ($maxIndex === 0) {
                $result .= $values[0];
                continue;
            }
            $randomIndex = $generator->generate(0, $maxIndex);
            $result .= $values[$randomIndex];
        }
    }
}
