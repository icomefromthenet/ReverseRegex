<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

/**
 * Provides a contract for components that can alternate between their children.
 *
 * This is typically used for regex alternation constructs (e.g., `a|b`).
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  0.0.1
 */
interface AlternateInterface
{
    /**
     * Instructs the component to select one of its children at random.
     */
    public function useAlternatingStrategy(): void;

    /**
     * Checks if the component is configured to use the alternating strategy.
     *
     * @return bool True if the alternating strategy is active, false otherwise.
     */
    public function usingAlternatingStrategy(): bool;
}
