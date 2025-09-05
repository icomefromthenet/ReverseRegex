<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use ReverseRegex\Random\GeneratorInterface as RandomGenerator;

/**
 * Provides a contract for components that can be repeated a variable number of times.
 *
 * This is used to handle regex quantifiers (e.g., `a*`, `b+`, `c{2,4}`).
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  0.0.1
 */
interface RepeatInterface
{
    /**
     * Gets the maximum number of times the component can occur.
     *
     * @return int The maximum number of occurrences.
     */
    public function getMaxOccurances(): int;

    /**
     * Sets the maximum number of times the component can occur.
     *
     * @param int $num The maximum number of occurrences.
     */
    public function setMaxOccurances(int $num): void;

    /**
     * Gets the minimum number of times the component must occur.
     *
     * @return int The minimum number of occurrences.
     */
    public function getMinOccurances(): int;

    /**
     * Sets the minimum number of times the component must occur.
     *
     * @param int $num The minimum number of occurrences.
     */
    public function setMinOccurances(int $num): void;

    /**
     * Returns the difference between the max and min occurrences.
     *
     * @return int The result of `max - min`.
     */
    public function getOccuranceRange(): int;

    /**
     * Calculates a random number of repeats based on the min/max range.
     *
     * @param RandomGenerator $generator The random number generator to use.
     * @return int A random integer between min and max (inclusive).
     */
    public function calculateRepeatQuota(RandomGenerator $generator): int;
}
