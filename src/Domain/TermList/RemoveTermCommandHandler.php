<?php

declare(strict_types=1);

namespace CinemaBot\Domain\TermList;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Group\GroupRepository;

final class RemoveTermCommandHandler implements CommandHandler
{
    private GroupRepository $repository;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(RemoveTermCommand $command): void
    {
        $id = $command->getId();
        $term = $command->getTerm();

        /** @var TermListUseCase $group */
        $group = $this->repository->load($id, fn(Events $events) => new TermListUseCase($events));
        $group->remove($term);

        $this->repository->save($group);
    }
}
