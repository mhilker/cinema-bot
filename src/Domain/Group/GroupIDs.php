<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Group;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class GroupIDs implements IteratorAggregate, Countable
{
    /** @var GroupID[] */
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

    private function add(GroupID $value): void
    {
        $this->values[] = $value;
    }

    /**
     * @return Traversable | GroupID[]
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
