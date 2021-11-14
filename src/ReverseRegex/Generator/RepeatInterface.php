<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

/**
 *  Represent a group has max and min number of occurances.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
interface RepeatInterface
{
    /**
     * Fetches the max re-occurances.
     *
     * @return int the maximum number of occurances
     */
    public function getMaxOccurances();

    /**
     *  Sets the maximum re-occurances.
     *
     *  @param int $num
     */
    public function setMaxOccurances($num);

    /**
     *  Fetch the Minimum re-occurances.
     *
     *  @return int
     */
    public function getMinOccurances();

    /**
     *  Sets the Minimum number of re-occurances.
     *
     *  @param int $num
     */
    public function setMinOccurances($num);

    /**
     *  Return the occurance range.
     *
     *  @return int the range
     */
    public function getOccuranceRange();
}
