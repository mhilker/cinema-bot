<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Group\GroupRepository;
use Exception;

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

        if ($this->exists($groupID)) {
            return;
        }

        $group = FoundGroupUseCase::foundNew($groupID, $chatID);
        $this->repository->save($group);
    }

    private function exists(GroupID $groupID): bool
    {
        try {
            $this->repository->load($groupID, fn(Events $events) => new FoundGroupUseCase($events));
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
}
