<?php

declare(strict_types=1);

namespace ReverseRegex\Random;

use ReverseRegex\Exception as ReverseRegexException;

/**
 *   Generator Factory.
 *
 *   @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class GeneratorFactory
{
    /**
     * @var string[] list of Generators
     *
     *  Each Generator must implement the ReverseRegex\RandomInterface
     */
    protected static $types = [
        'srand' => \ReverseRegex\Random\SrandRandom::class,
        'mersenne' => \ReverseRegex\Random\MersenneRandom::class,
        'simple' => \ReverseRegex\Random\SimpleRandom::class,
    ];

    /**
     * @param string $index
     * @param string $namespace
     *
     * @return string
     */
    public static function registerExtension($index, $namespace)
    {
        $index = strtolower($index);

        return self::$types[$index] = $namespace;
    }

    /**
     * @param array<string, string> $extension
     */
    public static function registerExtensions(array $extension): void
    {
        foreach ($extension as $key => $ns) {
            self::registerExtension($key, $ns);
        }
    }

    //  ----------------------------------------------------------------------------

    /**
     *  Resolve a Dcotrine DataType Class.
     *
     * @param string $type the random generator type name
     * @param int|null $seed
     *
     * @throws ReverseRegexException
     *
     * @return GeneratorInterface
     */
    public function create($type, $seed = null)
    {
        $type = strtolower($type);

        // check extension list

        if (isset(self::$types[$type]) === true) {
            // assign platform the full namespace
            if (class_exists(self::$types[$type]) === false) {
                throw new ReverseRegexException('Unknown Generator at::' . $type);
            }

            $type = self::$types[$type];
        } else {
            throw new ReverseRegexException('Unknown Generator at::' . $type);
        }

        return new $type($seed);
    }
}
// End of File
