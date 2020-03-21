<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Shows;

final class Notification
{
    private NotificationID $notificationID;
    private GroupID $groupID;
    private Shows $shows;

    public function __construct(NotificationID $notificationID, GroupID $groupID, Shows $shows)
    {
        $this->notificationID = $notificationID;
        $this->groupID = $groupID;
        $this->shows = $shows;
    }

    public function getNotificationID(): NotificationID
    {
        return $this->notificationID;
    }

    public function getGroupID(): GroupID
    {
        return $this->groupID;
    }

    public function getShows(): Shows
    {
        return $this->shows;
    }
}
