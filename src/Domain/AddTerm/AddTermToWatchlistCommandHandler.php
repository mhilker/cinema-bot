<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddTerm;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Repository\GroupRepository;

final class AddTermToWatchlistCommandHandler implements CommandHandler
{
    private GroupRepository $repository;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(AddTermToWatchlistCommand $command): void
    {
        $groupID = $command->getGroupID();
        $term = $command->getTerm();

        /** @var AddTermToWatchlistUseCase $group */
        $group = $this->repository->load($groupID, fn(Events $events) => new AddTermToWatchlistUseCase($events));
        $group->add($term);

        $this->repository->save($group);
    }
}
