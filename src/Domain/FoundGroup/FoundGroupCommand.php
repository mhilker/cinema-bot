<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\GroupID;

final class FoundGroupCommand implements Command
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
