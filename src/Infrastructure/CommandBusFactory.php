<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\DirectCommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\CrawlCinema\CrawlCinemaCommand;
use CinemaBot\Domain\CrawlCinema\CrawlCinemaCommandHandler;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommand;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommandHandler;
use CinemaBot\Domain\FoundGroup\FoundGroupCommand;
use CinemaBot\Domain\FoundGroup\FoundGroupCommandHandler;
use CinemaBot\Domain\TermList\AddTermCommand;
use CinemaBot\Domain\TermList\AddTermCommandHandler;
use CinemaBot\Domain\TermList\RemoveTermCommand;
use CinemaBot\Domain\TermList\RemoveTermCommandHandler;
use Psr\Container\ContainerInterface;

final class CommandBusFactory
{
    public function __invoke(ContainerInterface $container): CommandBus
    {
        $eventDispatcher = $container->get(EventDispatcher::class);

        $commandBus = new DirectCommandBus($eventDispatcher);
        $commandBus->add(CreateCinemaCommand::class, $container->get(CreateCinemaCommandHandler::class));
        $commandBus->add(CrawlCinemaCommand::class, $container->get(CrawlCinemaCommandHandler::class));
        $commandBus->add(AddTermCommand::class, $container->get(AddTermCommandHandler::class));
        $commandBus->add(RemoveTermCommand::class, $container->get(RemoveTermCommandHandler::class));
        $commandBus->add(FoundGroupCommand::class, $container->get(FoundGroupCommandHandler::class));
        return $commandBus;
    }
}
