<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class URLs implements IteratorAggregate
{
    /** @var URL[] */
    private $values = [];

    private function __construct(iterable $values)
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public static function from(iterable $values): self
    {
        return new self($values);
    }

    private function add(URL $value): void
    {
        $this->values[] = $value;
    }

    /**
     * @return Traversable | URL[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }
}
