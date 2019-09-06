<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CommandHandler;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Domain\Cinema\Cinema;
use CinemaBot\Domain\Cinema\CinemaRepository;
use CinemaBot\Domain\Command\CreateCinemaCommand;

final class CreateCinemaCommandHandler implements CommandHandler
{
    /** @var CinemaRepository */
    private $repository;

    public function __construct(CinemaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateCinemaCommand $command): void
    {
        $id = $command->getId();
        $url = $command->getURL();

        $cinema = Cinema::create($id, $url);

        $this->repository->save($cinema);
    }
}
