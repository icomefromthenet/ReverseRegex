<?php

declare(strict_types=1);

namespace ReverseRegex\Random;

use ReverseRegex\Exception as ReverseRegexException;

/**
 * A factory for creating random number generator instances.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class GeneratorFactory
{
    /**
     * @var array<string, class-string<GeneratorInterface>> A map of short names to generator class names.
     */
    private static array $types = [
      'mersenne' => MersenneRandom::class,
    ];

    /**
     * Registers a custom generator class.
     *
     * @param string $name The short name to register (e.g., 'my_generator').
     * @param class-string<GeneratorInterface> $class The fully qualified class name.
     */
    public static function registerExtension(string $name, string $class): void
    {
        self::$types[strtolower($name)] = $class;
    }

    /**
     * Registers multiple custom generator classes.
     *
     * @param array<string, class-string<GeneratorInterface>> $extensions
     */
    public static function registerExtensions(array $extensions): void
    {
        foreach ($extensions as $name => $class) {
            self::registerExtension($name, $class);
        }
    }

    /**
     * Creates a new random generator instance.
     *
     * @param string $type The short name of the generator (e.g., 'mersenne').
     * @param int|null $seed An optional seed for the generator.
     * @throws ReverseRegexException If the generator type is unknown.
     */
    public function create(string $type, ?int $seed = null): GeneratorInterface
    {
        $typeKey = strtolower($type);
        $class = self::$types[$typeKey] ?? null;

        if ($class === null || !class_exists($class)) {
            throw new ReverseRegexException('Unknown Generator type: ' . $type);
        }

        return new $class($seed);
    }
}
