<?php

declare(strict_types=1);

namespace ReverseRegex;

use ArrayAccess;

/**
 * Reads lexer tokens regardless of their representation.
 *
 * doctrine/lexer 1.x yields tokens as arrays, 2.x and 3.x as `Token` objects
 * (array access on them has been removed in 3.x).
 */
final class TokenHelper
{
    /**
     * @param array<string, mixed>|object|null $token
     *
     * @return mixed
     */
    public static function value($token)
    {
        return self::read($token, 'value');
    }

    /**
     * @param array<string, mixed>|object|null $token
     *
     * @return mixed
     */
    public static function type($token)
    {
        return self::read($token, 'type');
    }

    /**
     * @param array<string, mixed>|object|null $token
     *
     * @return mixed
     */
    public static function position($token)
    {
        return self::read($token, 'position');
    }

    /**
     * @param array<string, mixed>|object|null $token
     *
     * @return mixed
     */
    private static function read($token, string $property)
    {
        if ($token === null) {
            return null;
        }

        if (\is_array($token) || $token instanceof ArrayAccess) {
            return $token[$property];
        }

        return $token->{$property};
    }
}
