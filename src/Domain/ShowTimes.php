<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class ShowTimes implements IteratorAggregate, Countable
{
    /** @var ShowTime[] */
    private array $values = [];

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

    private function add(ShowTime $value): void
    {
        $key = $value->getValue()->format(DATE_ATOM);
        $this->values[$key] = $value;
    }

    /**
     * @return Traversable | ShowTime[]
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
