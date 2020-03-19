<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommand;
use CinemaBot\Domain\URL;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final class CreateCinemaAction implements RequestHandlerInterface
{
    private CommandBus $commandBus;
    private EventDispatcher $eventDispatcher;

    public function __construct(CommandBus $commandBus, EventDispatcher $eventDispatcher)
    {
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $cinemaID = CinemaID::from($body['cinemaID']);
        $url = URL::from($body['url']);

        $this->commandBus->dispatch(new CreateCinemaCommand($cinemaID, $url));
        $this->eventDispatcher->dispatch();

        return new Response(201);
    }
}
