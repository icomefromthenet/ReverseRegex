<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use PHPStats\Generator\GeneratorInterface;
use ReverseRegex\Exception as GeneratorException;

/**
 *  Base Class for Scopes.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
class Scope extends Node implements ContextInterface, RepeatInterface, AlternateInterface
{
    public const REPEAT_MIN_INDEX = 'repeat_min';

    public const REPEAT_MAX_INDEX = 'repeat_max';

    public const USE_ALTERNATING_INDEX = 'use_alternating';

    /**
     *  Class Constructor.
     */
    public function __construct($label = 'node')
    {
        parent::__construct($label);

        $this[self::USE_ALTERNATING_INDEX] = false;

        $this->setMinOccurances(1);
        $this->setMaxOccurances(1);
    }

    //  ----------------------------------------------------------------------------
    // Conext Interface

    /**
     *  Generate a text string appending to result arguments.
     *
     * @param string|null $result Initial value; `null` is treated as an empty string
     *
     * @return string
     */
    public function generate(&$result, GeneratorInterface $generator)
    {
        if ($this->count() === 0) {
            throw new GeneratorException('No child scopes to call must be atleast 1');
        }

        $repeat_x = $this->calculateRepeatQuota($generator);

        // rewind the current item
        $this->rewind();
        while ($repeat_x > 0) {
            if ($this->usingAlternatingStrategy()) {
                $randomIndex = round($generator->generate(1, ($this->count())));
                $this->get($randomIndex)->generate($result, $generator);
            } else {
                /** @var Scope $current */
                foreach ($this as $current) {
                    $current->generate($result, $generator);
                }
            }

            $repeat_x -= 1;
        }

        return $result;
    }

    /**
     *  Fetch a node given an `one-based index`.
     *
     * @param int|float $index
     *
     * @return Scope|null if none found
     */
    public function get($index)
    {
        if ($index > $this->count() || $index <= 0) {
            return null;
        }

        $this->rewind();
        while (($index - 1) > 0) {
            $this->next();
            $index -= 1;
        }

        /** @var Scope|null $current */
        $current = $this->current();

        return $current;
    }

    //  ----------------------------------------------------------------------------
    // Repeat Interface

    /**
     * Fetches the max occurances.
     *
     * @return int the maximum number of occurances
     */
    public function getMaxOccurances(): int
    {
        return $this[self::REPEAT_MAX_INDEX];
    }

    /**
     *  Sets the maximum re-occurances.
     *
     * @param mixed $num
     */
    public function setMaxOccurances($num): void
    {
        if (\is_int($num) === false) {
            throw new GeneratorException('Number must be an integer');
        }

        $this[self::REPEAT_MAX_INDEX] = $num;
    }

    /**
     *  Fetch the Minimum Occurances.
     */
    public function getMinOccurances(): int
    {
        return $this[self::REPEAT_MIN_INDEX];
    }

    /**
     *  Sets the Minimum number of re-occurances.
     *
     * @param mixed $num
     */
    public function setMinOccurances($num): void
    {
        if (\is_int($num) === false) {
            throw new GeneratorException('Number must be an integer');
        }

        $this[self::REPEAT_MIN_INDEX] = $num;
    }

    /**
     *  Return the occurance range.
     *
     * @return int the range
     */
    public function getOccuranceRange(): int
    {
        return (int) ($this->getMaxOccurances() - $this->getMinOccurances());
    }

    /**
     *  Calculate a random numer of repeats given the current min-max range.
     */
    public function calculateRepeatQuota(GeneratorInterface $generator): int
    {
        $repeat_x = $this->getMinOccurances();

        if ($this->getOccuranceRange() > 0) {
            $repeat_x = (int) round($generator->generate($this->getMinOccurances(), $this->getMaxOccurances()));
        }

        return $repeat_x;
    }

    // ------------------------------------------------------------------
    // AlternateInterface

    /**
     *  Tell the scope to select childing use alternating strategy.
     */
    public function useAlternatingStrategy(): void
    {
        $this[self::USE_ALTERNATING_INDEX] = true;
    }

    /**
     *  Return true if setting been activated.
     *
     * @return bool true
     */
    public function usingAlternatingStrategy(): bool
    {
        return (bool) $this[self::USE_ALTERNATING_INDEX];
    }
}
// End of File
