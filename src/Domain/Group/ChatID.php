<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Group;

use CinemaBot\Domain\Group\InvalidChatIdException;

final class ChatID
{
    private string $value;

    /**
     * @throws InvalidChatIdException
     */
    private function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidChatIdException('Chat ID must not be empty');
        }
        $this->value = $value;
    }

    /**
     * @throws InvalidChatIdException
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * @throws InvalidChatIdException
     */
    public static function fromInt(int $value): self
    {
        return new self((string) $value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
