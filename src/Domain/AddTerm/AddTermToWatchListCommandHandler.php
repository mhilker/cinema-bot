<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddTerm;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Group\GroupRepository;

final class AddTermToWatchListCommandHandler implements CommandHandler
{
    private GroupRepository $repository;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(AddTermToWatchListCommand $command): void
    {
        $groupID = $command->getGroupID();
        $term = $command->getTerm();

        /** @var AddTermToWatchListUseCase $group */
        $group = $this->repository->load($groupID, fn(Events $events) => new AddTermToWatchListUseCase($events));
        $group->add($term);

        $this->repository->save($group);
    }
}
