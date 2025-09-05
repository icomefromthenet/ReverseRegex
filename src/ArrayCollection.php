<?php

declare(strict_types=1);

namespace ReverseRegex;

use Doctrine\Common\Collections\ArrayCollection as BaseCollection;

/**
 * @template TKey of array-key
 * @template T
 * @extends BaseCollection<TKey, T>
 */
class ArrayCollection extends BaseCollection
{
    /**
     * Sorts the collection by its keys in ascending order.
     *
     * @return $this
     */
    public function sort(): self
    {
        $elements = $this->toArray();
        ksort($elements);

        $this->clear();

        foreach ($elements as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Fetches a value at a given 1-based position.
     *
     * @param int $position The 1-based position of the element to retrieve.
     * @return T|null The element at the specified position, or null if the position is invalid.
     */
    public function getAt(int $position): mixed
    {
        if ($position < 1 || $position > $this->count()) {
            return null;
        }

        // The slice method is more efficient than iterating.
        $slice = $this->slice($position - 1, 1);

        // array_shift returns the first element of an array, or null if the array is empty.
        return array_shift($slice);
    }
}
