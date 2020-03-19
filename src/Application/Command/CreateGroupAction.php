<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\FoundGroup\FoundGroupCommand;
use CinemaBot\Domain\GroupID;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final class CreateGroupAction implements RequestHandlerInterface
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

        $groupID = GroupID::from($body['groupID']);
        $chatID = ChatID::fromString($body['chatID']);

        $this->commandBus->dispatch(new FoundGroupCommand($groupID, $chatID));
        $this->eventDispatcher->dispatch();

        return new Response(201);
    }
}
