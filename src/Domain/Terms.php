<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class Terms implements IteratorAggregate, Countable
{
    /** @var Term[] */
    private array $terms = [];

    private function __construct(iterable $terms)
    {
        foreach ($terms as $term) {
            $this->add($term);
        }
    }

    public static function from(iterable $terms): Terms
    {
        return new self($terms);
    }

    private function add(Term $term): void
    {
        $this->terms[$term->asString()] = $term;
    }

    /**
     * @return Traversable | Term[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->terms);
    }

    public function count(): int
    {
        return count($this->terms);
    }
}
