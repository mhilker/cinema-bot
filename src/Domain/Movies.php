<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Movies implements IteratorAggregate
{
    /** @var Movie[] */
    private $movies = [];

    public function __construct(iterable $movies)
    {
        foreach ($movies as $movie) {
            $this->add($movie);
        }
    }

    public static function from(iterable $movies): Movies
    {
        return new self($movies);
    }

    public function add($movie): void
    {
        $this->movies[] = $movie;
    }

    /**
     * @return Traversable | Movie[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->movies);
    }

    public function getByName(string $movieName): ?Movie
    {
        foreach ($this->movies as $movie) {
            if ($movieName === $movie->getName()) {
                return $movie;
            }
        }

        return null;
    }
}
