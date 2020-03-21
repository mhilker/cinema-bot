<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Cinema\CinemaID;
use CinemaBot\Domain\Cinema\CinemaRepository;

final class CreateCinemaCommandHandler implements CommandHandler
{
    private CinemaRepository $repository;

    public function __construct(CinemaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateCinemaCommand $command): void
    {
        $id = $command->getCinemaID();
        $url = $command->getURL();

        if ($this->exists($id)) {
            return;
        }

        $cinema = CreateCinemaUseCase::createNew($id, $url);

        $this->repository->save($cinema);
    }

    public function exists(CinemaID $id): bool
    {
        try {
            $this->repository->load($id, fn(Events $events) => new CreateCinemaUseCase($events));
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
