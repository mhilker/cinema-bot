<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Infrastructure\Telegram\TelegramToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

final class WebHookAction
{
    /** @var Client */
    private $bot;

    public function __construct(Client $bot)
    {
        $this->bot = $bot;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->bot->run();

        return $response;
    }
}
