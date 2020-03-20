<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupProjector;
use CinemaBot\Domain\CinemaList\CinemaListProjector;
use CinemaBot\Domain\MovieList\MovieListProjector;
use CinemaBot\Domain\ShowList\ShowListProjector;
use CinemaBot\Domain\WatchList\WatchListProjector;
use Psr\Container\ContainerInterface;

final class EventBusFactory
{
    public function __invoke(ContainerInterface $container): DirectEventBus
    {
        $eventBus = new DirectEventBus();
        $eventBus->add($container->get(WatchListProjector::class));
        $eventBus->add($container->get(CinemaListProjector::class));
        $eventBus->add($container->get(ChatGroupProjector::class));
        $eventBus->add($container->get(ShowListProjector::class));
        $eventBus->add($container->get(MovieListProjector::class));
        return $eventBus;
    }
}
