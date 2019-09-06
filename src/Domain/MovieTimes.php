<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class MovieTimes implements IteratorAggregate, Countable
{
    /** @var MovieTime[] */
    private $values = [];

    private function __construct(iterable $values)
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public static function from(iterable $times): self
    {
        return new self($times);
    }

    private function add(MovieTime $value): void
    {
        $key = $value->getValue()->format(DATE_ATOM);
        $this->values[$key] = $value;
    }

    /**
     * @return Traversable | MovieTime[]
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
