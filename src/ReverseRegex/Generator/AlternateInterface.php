<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

/**
 *  Allows a scope to select children using alternating strategy.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
interface AlternateInterface
{
    /**
     *  Tell the scope to select childing use alternating strategy.
     */
    public function useAlternatingStrategy(): void;

    /**
     *  Return true if setting been activated.
     *
     * @return bool true
     */
    public function usingAlternatingStrategy();
}
// End of File
