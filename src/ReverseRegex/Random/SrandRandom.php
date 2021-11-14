<?php

declare(strict_types=1);

namespace ReverseRegex\Random;

/*
 * class SrandRandom
 *
 * Wrapper to mt_random with seed option
 *
 * Won't work when suhosin.srand.ignore = Off or suhosin.mt_srand.ignore = Off
 * is set.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 */
class SrandRandom implements GeneratorInterface
{
    /**
     *  @var int the seed to use on each pass
     */
    protected $seed;

    /**
     *  @var int the max
     */
    protected $max;

    /**
     *  @var int the min
     */
    protected $min;

    /*
     * __construct()
     *
     * @param integer $seed the starting seed
     * @return void
     * @access public
     */
    public function __construct($seed = 0)
    {
        $this->seed($seed);
    }

    /**
     *  Return the maxium random number.
     *
     *  @return float
     */
    public function max($value = null)
    {
        if (null === $value && null === $this->max) {
            $max = getrandmax();
        } elseif (null === $value) {
            $max = $this->max;
        } else {
            $max = $this->max = $value;
        }

        return $max;
    }

    public function min($value = null)
    {
        if (null === $value && null === $this->max) {
            $min = 0;
        } elseif (null === $value) {
            $min = $this->min;
        } else {
            $min = $this->min = $value;
        }

        return $min;
    }

    /**
     *  Generate a value between $min - $max.
     *
     *  @param int $max
     *  @param int $max
     */
    public function generate($min = 0, $max = null)
    {
        if (null === $max) {
            $max = $this->max;
        }

        if (null === $min) {
            $min = $this->min;
        }

        return rand($min, $max);
    }

    /**
     *  Set the seed to use.
     *
     *  @param $seed integer the seed to use
     */
    public function seed($seed = null)
    {
        $this->seed = $seed;
        srand($this->seed);

        return $this;
    }
}
