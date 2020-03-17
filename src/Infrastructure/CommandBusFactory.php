<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\DirectCommandBus;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommand;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommandHandler;
use CinemaBot\Domain\AddTerm\AddTermToWatchlistCommand;
use CinemaBot\Domain\AddTerm\AddTermToWatchlistCommandHandler;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommand;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommandHandler;
use CinemaBot\Domain\FoundGroup\FoundGroupCommand;
use CinemaBot\Domain\FoundGroup\FoundGroupCommandHandler;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommand;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommandHandler;
use Psr\Container\ContainerInterface;

final class CommandBusFactory
{
    public function __invoke(ContainerInterface $container): CommandBus
    {
        $commandBus = new DirectCommandBus();
        $commandBus->add(CreateCinemaCommand::class, $container->get(CreateCinemaCommandHandler::class));
        $commandBus->add(CrawlCinemaCommand::class, $container->get(CrawlCinemaCommandHandler::class));
        $commandBus->add(AddTermToWatchlistCommand::class, $container->get(AddTermToWatchlistCommandHandler::class));
        $commandBus->add(RemoveFromWatchlistCommand::class, $container->get(RemoveFromWatchlistCommandHandler::class));
        $commandBus->add(FoundGroupCommand::class, $container->get(FoundGroupCommandHandler::class));
        return $commandBus;
    }
}
