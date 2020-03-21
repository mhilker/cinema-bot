<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Domain\Group\GroupRepository;

final class FoundGroupCommandHandler implements CommandHandler
{
    private GroupRepository $repository;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FoundGroupCommand $command): void
    {
        $groupID = $command->getGroupID();
        $chatID = $command->getChatID();

        $group = FoundGroupUseCase::foundNew($groupID, $chatID);

        $this->repository->save($group);
    }
}
