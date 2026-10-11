<?php

declare(strict_types=1);

namespace ReverseRegex\Random;

use PHPStats\Generator\GeneratorInterface as CommonInterface;

/**
 *  Interface that all generators should implement.
 */
interface GeneratorInterface extends CommonInterface
{
    /**
     *  Generate a value between $min - $max.
     *
     * @param int $min
     * @param int|null $max
     *
     * @return int|float
     */
    public function generate($min = 0, $max = null);

    /**
     *  Set the seed to use.
     *
     * @param int|null $seed the seed to use
     *
     * @return mixed
     */
    public function seed($seed = null);

    /**
     *  Return the hights possible random value.
     *
     * @return int|float|null
     */
    public function max();
}
// End of File
