<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use Doctrine\DBAL\Driver\Connection;

final class DoctrineNotificationProjection implements NotificationProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(): Notifications
    {
        return Notifications::from([]);
    }

    public function ack(NotificationID $id): void
    {
        // TODO: Implement ack() method.
    }

    public function nack(NotificationID $id): void
    {
        // TODO: Implement nack() method.
    }
}
