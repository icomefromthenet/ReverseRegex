<?php

declare(strict_types=1);

namespace ReverseRegex\Generator;

use PHPStats\Generator\GeneratorInterface;
use ReverseRegex\ArrayCollection;
use ReverseRegex\Exception as GeneratorException;

/**
 *  Scope for Literal Values.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
class LiteralScope extends Scope
{
    /**
     * @var ArrayCollection container for literals values
     */
    protected $literals;

    /**
     *  Class Constructor.
     *
     * @param string $label
     */
    public function __construct($label = 'label')
    {
        parent::__construct($label);

        $this->literals = new ArrayCollection();
    }

    /**
     *  Adds a literal value to internal collection.
     *
     * @param mixed $literal
     */
    public function addLiteral($literal): void
    {
        $this->literals->add($literal);
    }

    /**
     *  Sets a value on the internal collection using a key.
     *
     * @param int|string $hex a hexidecimal number or the code point
     * @param string $literal the literal to store
     */
    public function setLiteral($hex, $literal): void
    {
        $this->literals->set($hex, $literal);
    }

    /**
     *  Return the literal ArrayCollection.
     *
     * @return ArrayCollection
     */
    public function getLiterals()
    {
        return $this->literals;
    }

    /**
     *  Generate a text string appending to the result argument.
     *
     * @param string|null $result Initial value; `null` is treated as an empty string
     *
     * @return string
     */
    public function generate(&$result, GeneratorInterface $generator)
    {
        if ($this->literals->count() === 0) {
            throw new GeneratorException('There are no literals to choose from');
        }

        $repeat_x = $this->calculateRepeatQuota($generator);

        while ($repeat_x > 0) {
            $randomIndex = 1;

            if ($this->literals->count() > 1) {
                $randomIndex = round($generator->generate(1, ($this->literals->count())));
            }

            $result .= $this->literals->getAt($randomIndex);

            --$repeat_x;
        }

        return $result;
    }
}
// End of File
