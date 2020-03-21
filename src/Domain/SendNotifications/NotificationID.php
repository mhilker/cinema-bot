<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Infrastructure\UUID;

final class NotificationID
{
    private string $value;

    /**
     * @throws InvalidNotificationIDException
     */
    private function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidNotificationIDException('Notification ID must not be empty');
        }
        $this->value = $value;
    }

    /**
     * @throws InvalidNotificationIDException
     */
    public static function from(string $value): self
    {
        return new self($value);
    }

    /**
     * @throws InvalidNotificationIDException
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
