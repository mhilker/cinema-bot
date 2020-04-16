<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use TelegramBot\Api\Client;
use Throwable;

final class TelegramWebHookAction implements RequestHandlerInterface
{
    private Client $telegram;
    private LoggerInterface $logger;

    public function __construct(Client $telegram, LoggerInterface $logger)
    {
        $this->telegram = $telegram;
        $this->logger = $logger;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // TODO: inject request
            $this->telegram->run();
        } catch (Throwable $error) {
            $this->logger->error('An error occurred', ['error' => $error]);
            return new Response(500);
        }

        return new Response(200);
    }
}
