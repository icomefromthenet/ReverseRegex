<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use ReverseRegex\Random\GeneratorInterface as RandomGenerator;

/**
 * Defines the contract for a component that can generate a string representation.
 *
 * Classes implementing this interface are nodes in the parsed regular expression
 * tree and are responsible for generating a part of the final output string.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  0.0.1
 */
interface ContextInterface
{
    /**
     * Generates a text string and appends it to the provided result variable.
     *
     * @param string          $result    The string to which the generated text will be appended (passed by reference).
     * @param RandomGenerator $generator The random number generator for making choices.
     */
    public function generate(string &$result, RandomGenerator $generator): void;
}
