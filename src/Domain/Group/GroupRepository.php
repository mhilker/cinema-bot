<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Group;

interface GroupRepository
{
    public function save(GroupUseCase $group): void;

    public function load(GroupID $id, callable $callable): GroupUseCase;
}
