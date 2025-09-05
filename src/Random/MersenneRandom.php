<?php

declare(strict_types=1);

namespace ReverseRegex\Random;

/**
 * A random number generator using PHP's native mt_rand() function (Mersenne Twister).
 *
 * This generator is fast and suitable for all non-cryptographic purposes,
 * which is ideal for generating sample data.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class MersenneRandom implements GeneratorInterface
{
    /**
     * @param int|null $seed An optional seed to initialize the random number generator.
     */
    public function __construct(?int $seed = null)
    {
        $this->seed($seed);
    }

    /**
     * {@inheritDoc}
     */
    public function generate(int $min = 0, ?int $max = null): int
    {
        $max ??= $this->max();

        return mt_rand($min, $max);
    }

    /**
     * {@inheritDoc}
     */
    public function seed(?int $seed = null): void
    {
        // Seeds the global Mersenne Twister generator.
        // Calling with null resets to a random seed.
        mt_srand($seed);
    }

    /**
     * {@inheritDoc}
     */
    public function max(): int
    {
        return mt_getrandmax();
    }
}
