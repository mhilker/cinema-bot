<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class MovieTimes implements IteratorAggregate
{
    private $values = [];

    public function __construct(array $array)
    {
        foreach ($array as $value) {
            $this->add($value);
        }
    }

    public function add(MovieTime $value): void
    {
        $this->values[] = $value;
    }

    /**
     * @return Traversable | MovieTime[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }
}
