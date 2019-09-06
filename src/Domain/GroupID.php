<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use CinemaBot\Infrastructure\UUID;

final class GroupID
{
    /** @var string */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

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
