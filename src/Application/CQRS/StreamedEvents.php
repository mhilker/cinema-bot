<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

use Traversable;

final class StreamedEvents implements Events
{
    private iterable $source;

    private function __construct(iterable $source)
    {
        $this->source = $source;
    }

    public static function from(iterable $source): Events
    {
        return new self($source);
    }

    /**
     * @return Traversable | Event[]
     */
    public function getIterator(): Traversable
    {
        yield from $this->source;
    }
}
