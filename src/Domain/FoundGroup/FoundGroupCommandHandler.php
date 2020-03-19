<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\GroupFoundedEvent;
use CinemaBot\Domain\FoundGroup\FoundGroupUseCase;
use CinemaBot\Domain\Repository\GroupRepository;

final class FoundGroupCommandHandler implements CommandHandler
{
    private GroupRepository $repository;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FoundGroupCommand $command): void
    {
        $group = FoundGroupUseCase::foundNew($command->getGroupID(), $command->getChatID());

        $this->repository->save($group);
    }
}
