<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications\Projection;

use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Notifications\Notification;
use CinemaBot\Domain\Notifications\NotificationID;
use CinemaBot\Domain\Notifications\Notifications;
use CinemaBot\Domain\Shows;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Driver\Connection;
use Exception;

final class DoctrineNotificationQueueProjection implements NotificationQueueProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function enqueue(Notifications $notifications): void
    {
        $this->connection->beginTransaction();

        try {
            $sql = <<<SQL
            INSERT INTO "notifications" ("notification_id", "group_id", "shows")
            VALUES (:notification_id, :group_id, :shows);
            SQL;

            $statement = $this->connection->prepare($sql);

            foreach ($notifications as $notification) {
                $statement->execute([
                    'notification_id' => $notification->getNotificationID()->asString(),
                    'group_id'        => $notification->getGroupID()->asString(),
                    'shows'           => json_encode($notification->getShows()->asArray(), JSON_THROW_ON_ERROR),
                ]);
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw new Exception('Could not enqueue notifications', 0, $exception);
        }
    }

    public function dequeue(): Notifications
    {
        $sql = <<<'SQL'
        SELECT * 
        FROM "notifications" 
        WHERE "notified" IS NULL 
          AND "failed" IS NULL;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([]);

        $notifications = [];
        while ($row = $statement->fetch()) {
            $id = NotificationID::from($row['notification_id']);
            $groupID = GroupID::from($row['group_id']);
            $shows = Shows::fromArray(json_decode($row['shows'], true, 512, JSON_THROW_ON_ERROR));
            $notifications[] = new Notification($id, $groupID, $shows);
        }

        return Notifications::from($notifications);
    }

    public function isEmpty(): bool
    {
        $sql = <<<'SQL'
        SELECT COUNT(*) AS "count" 
        FROM "notifications" 
        WHERE "notified" IS NULL 
          AND "failed" IS NULL;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([]);

        $row = $statement->fetch();
        return ((int) $row['count']) === 0;
    }

    public function ack(NotificationID $id): void
    {
        $sql = <<<'SQL'
        UPDATE "notifications" 
        SET "notified" = :now 
        WHERE "notification_id" = :notification_id;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'notification_id' => $id->asString(),
            'now'             => (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DATE_ATOM),
        ]);
    }

    public function nack(NotificationID $id): void
    {
        $sql = <<<'SQL'
        UPDATE "notifications" 
        SET "failed" = :now 
        WHERE "notification_id" = :notification_id;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'notification_id' => $id->asString(),
            'now'             => (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DATE_ATOM),
        ]);
    }
}
