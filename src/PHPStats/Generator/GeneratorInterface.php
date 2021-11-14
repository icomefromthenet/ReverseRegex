<?php

declare(strict_types=1);

namespace PHPStats\Generator;

/**
 *  Interface that all generators should implement.
 */
interface GeneratorInterface
{
    /**
     *  Generate a value between $min - $max.
     *
     *  @param int $max
     *  @param int $max
     */
    public function generate($min = 0, $max = null);

    /**
     *  Set the seed to use.
     *
     *  @param $seed integer the seed to use
     */
    public function seed($seed = null);

    /**
     *  Return the hights possible random value.
     *
     *  @return float
     */
    public function max();
}
