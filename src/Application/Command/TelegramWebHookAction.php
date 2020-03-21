<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use TelegramBot\Api\Client;

final class TelegramWebHookAction implements RequestHandlerInterface
{
    private Client $telegram;

    public function __construct(Client $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: inject request
        $this->telegram->run();

        return new Response(200);
    }
}
