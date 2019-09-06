<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Domain\GroupID;

final class GroupFoundedEvent
{
    /** @var GroupID */
    private $groupID;

    public function __construct(GroupID $groupID)
    {
        $this->groupID = $groupID;
    }

    public function getGroupID(): GroupID
    {
        return $this->groupID;
    }
}
