<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use TelegramBot\Api\Client;

final class WebHookAction implements RequestHandlerInterface
{
    private Client $bot;

    public function __construct(Client $bot)
    {
        $this->bot = $bot;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->bot->run();

        return new Response(200);
    }
}
