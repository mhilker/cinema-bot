<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use Closure;
use Traversable;

final class StreamedStorableEvents implements StorableEvents
{
    private Closure $source;

    private function __construct(Closure $source)
    {
        $this->source = $source;
    }

    public static function from(Closure $source): StorableEvents
    {
        return new self($source);
    }

    /**
     * @return Traversable | StorableEvent[]
     */
    public function getIterator(): Traversable
    {
        yield from ($this->source)();
    }
}
