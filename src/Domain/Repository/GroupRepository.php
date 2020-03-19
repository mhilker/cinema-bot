<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Repository;

use CinemaBot\Domain\GroupID;

interface GroupRepository
{
    public function save(GroupUseCase $group): void;

    public function load(GroupID $groupID, callable $callable): GroupUseCase;
}
