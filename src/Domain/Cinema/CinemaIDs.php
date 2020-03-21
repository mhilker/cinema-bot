<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class CinemaIDs implements IteratorAggregate
{
    /** @var CinemaID[] */
    private array $values = [];

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

    private function add(CinemaID $value): void
    {
        $this->values[] = $value;
    }

    /**
     * @return Traversable | CinemaID[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }
}
