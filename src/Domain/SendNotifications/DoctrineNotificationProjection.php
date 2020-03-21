<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\GroupList\GroupListProjection;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use CinemaBot\Domain\ShowTimes;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineNotificationProjection implements NotificationProjection
{
    private Connection $connection;
    private GroupListProjection $groupList;

    public function __construct(Connection $connection, GroupListProjection $groupList)
    {
        $this->connection = $connection;
        $this->groupList = $groupList;
    }

    public function fetch(): Notifications
    {
        $groupIds = $this->groupList->load();

        $notifications = [];
        foreach ($groupIds as $groupId) {
            $id = NotificationID::random();
            $shows = Shows::from([
                Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::from(new \DateTimeImmutable())])),
            ]);
            $notifications[] = new Notification($id, $groupId, $shows);
        }

        return Notifications::from($notifications);
    }

    public function ack(NotificationID $id): void
    {
        print_r($id);
    }

    public function nack(NotificationID $id): void
    {
        print_r($id);
    }
}
