<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class Movies implements IteratorAggregate, Countable
{
    /** @var Movie[] */
    private array $movies = [];

    private function __construct(iterable $movies)
    {
        foreach ($movies as $movie) {
            $this->add($movie);
        }
    }

    public static function from(iterable $movies): self
    {
        return new self($movies);
    }

    private function add(Movie $movie): void
    {
        $this->movies[] = $movie;
    }

    public function filter(callable $callable): self
    {
        return self::from(array_filter($this->movies, $callable));
    }

    /**
     * @return Traversable | Movie[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->movies);
    }

    public function count(): int
    {
        return count($this->movies);
    }
}
