<?php

declare(strict_types=1);

namespace ReverseRegex\Random;

/**
 *  Simple Random.
 *
 *  @see http://www.sitepoint.com/php-random-number-generator/
 *
 *  @author Craig Buckler
 */
class SimpleRandom implements GeneratorInterface
{
    /**
     * @var int the seed value to use
     */
    protected $seed = 0;

    /**
     * @var int the max
     */
    protected $max;

    /**
     * @var int the min
     */
    protected $min;

    /**
     *  Constructor.
     *
     * @param int|null $seed
     *
     * @return void
     */
    public function __construct($seed = null)
    {
        if ($seed === null || $seed === 0) {
            $this->seed(mt_rand());
        }

        $this->seed($seed);
    }

    /**
     * @param int|null $value
     *
     * @return int|null
     */
    public function max($value = null)
    {
        if ($value === null && $this->max === null) {
            $max = 2_147_483_647;
        } elseif ($value === null) {
            $max = $this->max;
        } else {
            $max = $this->max = $value;
        }

        return $max;
    }

    /**
     * @param int|null $value
     *
     * @return int|null
     */
    public function min($value = null)
    {
        if ($value === null && $this->max === null) {
            $min = 0;
        } elseif ($value === null) {
            $min = $this->min;
        } else {
            $min = $this->min = $value;
        }

        return $min;
    }

    /**
     *  Set the seed to use for generator.
     *
     * @param int|null $seed the seed to use
     *
     * @return int
     */
    public function seed($seed = null)
    {
        if ($seed === null) {
            $seed = 0;
        }

        return $this->seed = abs((int) $seed) % 9_999_999 + 1;
    }

    /**
     *  Generate a random numer.
     *
     * @param int $min
     * @param int|null $max 2,796,203 largest possible max
     *
     * @return int
     */
    public function generate($min = 0, $max = null)
    {
        if ($max === null) {
            $max = 2_796_203;
        }

        // Cap extremely large values (e.g., PHP_INT_MAX from unbounded quantifiers like * or +)
        // to a reasonable maximum to prevent memory exhaustion
        if ($max > 2_796_203) {
            $max = 2_796_203;
        }

        if ($this->seed == 0) {
            $this->seed(mt_rand());
        }

        $this->seed = ($this->seed * 125) % 2_796_203;

        return $this->seed % ($max - $min + 1) + $min;
    }
}
// End of File
