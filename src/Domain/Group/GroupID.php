<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Group;

use CinemaBot\Application\EventStream\EventStreamID;
use CinemaBot\Domain\Group\InvalidGroupIDException;
use CinemaBot\Infrastructure\UUID;

final class GroupID implements EventStreamID
{
    private string $value;

    /**
     * @throws InvalidGroupIDException
     */
    private function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidGroupIDException('Group ID must not be empty');
        }
        $this->value = $value;
    }

    /**
     * @throws InvalidGroupIDException
     */
    public static function from(string $value): self
    {
        return new self($value);
    }

    /**
     * @throws InvalidGroupIDException
     */
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
