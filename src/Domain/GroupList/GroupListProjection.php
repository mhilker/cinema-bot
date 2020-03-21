<?php

declare(strict_types=1);

namespace CinemaBot\Domain\GroupList;

use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Group\GroupIDs;

interface GroupListProjection
{
    public function load(): GroupIDs;

    public function add(GroupID $id): void;
}
