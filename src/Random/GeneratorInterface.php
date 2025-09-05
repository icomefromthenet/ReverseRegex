<?php

declare(strict_types=1);

namespace ReverseRegex\Random;

/**
 * Interface that all random number generators must implement.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 0.0.1
 */
interface GeneratorInterface
{
    /**
     * Generate a random integer value between $min and $max.
     *
     * @param int $min The lower bound of the random number.
     * @param int|null $max The upper bound of the random number. If null, the generator's maximum value should be used.
     * @return int
     */
    public function generate(int $min = 0, ?int $max = null): int;

    /**
     * Set the seed to use for the random number generator.
     *
     * @param int|null $seed The seed to use.
     * @return void
     */
    public function seed(?int $seed = null): void;

    /**
     * Return the highest possible random value that can be generated.
     *
     * @return int
     */
    public function max(): int;
}
