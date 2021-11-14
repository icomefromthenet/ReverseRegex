<?php

declare(strict_types=1);

namespace ReverseRegex\Parser;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;

/**
 *  Interface for all parser strategy object.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
interface StrategyInterface
{
    /**
     *  Parse the current token and return a new head.
     *
     *  @param ReverseRegex\Generator\Scope $head
     *  @param ReverseRegex\Generator\Scope $set
     *  @param ReverseRegex\Lexer $lexer
     *
     *  @return ReverseRegex\Generator\Scope a new head
     */
    public function parse(Scope $head, Scope $set, Lexer $lexer);
}
