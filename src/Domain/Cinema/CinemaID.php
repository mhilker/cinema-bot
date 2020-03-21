<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use CinemaBot\Application\EventStream\EventStreamID;
use CinemaBot\Domain\Cinema\InvalidCinemaIDException;
use CinemaBot\Infrastructure\UUID;

final class CinemaID implements EventStreamID
{
    private string $value;

    /**
     * @throws InvalidCinemaIDException
     */
    private function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidCinemaIDException('Cinema ID must not be empty');
        }
        $this->value = $value;
    }

    /**
     * @throws InvalidCinemaIDException
     */
    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function random(): self
    {
        $id = UUID::generateV4();

        return new self($id);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
