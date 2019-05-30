<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class MovieTimes implements IteratorAggregate, Countable
{
    private $values = [];

    public function __construct(iterable $values)
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public static function from(iterable $movies): MovieTimes
    {
        return new self($movies);
    }

    public function add(MovieTime $value): void
    {
        $key = $value->getValue()->format(DATE_ATOM);
        $this->values[$key] = $value;
    }

    public function remove(MovieTime $value): void
    {
        $key = $value->getValue()->format(DATE_ATOM);
        unset($this->values[$key]);
    }

    /**
     * @return Traversable<MovieTime>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    public function count(): int
    {
        return count($this->values);
    }
}
