<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Watchlist;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Watchlist implements IteratorAggregate
{
    /** @var Term[] */
    private $terms = [];

    public function __construct(iterable $terms)
    {
        foreach ($terms as $term) {
            $this->add($term);
        }
    }

    public static function from(iterable $terms): Watchlist
    {
        return new self($terms);
    }

    public function add(Term $term): void
    {
        $this->terms[] = $term;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->terms);
    }
}
