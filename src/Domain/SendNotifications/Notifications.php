<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use ArrayIterator;
use Exception;
use Traversable;

final class Notifications implements \IteratorAggregate, \Countable
{
    /** @var array<Notification> */
    private array $notifications = [];

    public function __construct(array $notifications)
    {
        foreach ($notifications as $notification) {
            $this->add($notification);
        }
    }

    public static function from(array $notifications): self
    {
        return new self($notifications);
    }

    private function add(Notification $notification): void
    {
        $this->notifications[] = $notification;
    }

    /**
     * @return Traversable | array<Notification>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->notifications);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->notifications);
    }
}
