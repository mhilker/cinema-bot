<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use ArrayIterator;
use CinemaBot\Domain\CinemaID;
use IteratorAggregate;
use Traversable;

final class CinemaIDs implements IteratorAggregate
{
    /** @var CinemaID[] */
    private $values = [];

    public function __construct(iterable $values)
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public static function from(iterable $values): CinemaIDs
    {
        return new self($values);
    }

    public function add(CinemaID $value): void
    {
        $this->values[] = $value;
    }

    /**
     * @return Traversable<CinemaID>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }
}
