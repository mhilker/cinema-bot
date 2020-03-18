<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Domain\Repository\CinemaRepository;

final class CreateCinemaCommandHandler implements CommandHandler
{
    private CinemaRepository $repository;

    public function __construct(CinemaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateCinemaCommand $command): void
    {
        $id = $command->getID();
        $url = $command->getURL();

        $cinema = CreateCinemaUseCase::createNew($id, $url);

        $this->repository->save($cinema);
    }
}
