<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use PHPStats\Generator\GeneratorInterface;

/**
 *  Conext interface for Generator.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
interface ContextInterface
{
    /**
     *  Generate a text string appending to result arguments.
     *
     *  @param string $result
     */
    public function generate(&$result, GeneratorInterface $generator);
}
