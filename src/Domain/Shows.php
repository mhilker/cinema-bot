<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class Shows implements IteratorAggregate, Countable
{
    /** @var Show[] */
    private array $shows = [];

    private function __construct(iterable $shows)
    {
        foreach ($shows as $show) {
            $this->add($show);
        }
    }

    public static function from(iterable $shows): self
    {
        return new self($shows);
    }

    private function add(Show $show): void
    {
        $this->shows[] = $show;
    }

    public function filter(callable $callable): self
    {
        return self::from(array_filter($this->shows, $callable));
    }

    /**
     * @return Traversable | Show[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->shows);
    }

    public function count(): int
    {
        return count($this->shows);
    }

    public static function fromArray(array $data): self
    {
        $shows = [];
        foreach ($data as $item) {
            $shows[] = Show::fromArray($item);
        }

        return new self($shows);
    }

    public function asArray(): array
    {
        $shows = [];
        foreach ($this->shows as $show) {
            $shows[] = $show->asArray();
        }
        return $shows;
    }
}
