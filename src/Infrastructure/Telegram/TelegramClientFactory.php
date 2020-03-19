<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Infrastructure\Telegram\Command\AddToWatchlistTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\HelpTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\JoinChatTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\LeaveChatTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\RemoveFromWatchlistTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\ShowWatchlistTelegramCommand;
use Psr\Container\ContainerInterface;
use TelegramBot\Api\Client;

final class TelegramClientFactory
{
    public function __invoke(ContainerInterface $container): Client
    {
        $token = TelegramToken::get();

        $commands = [
            $container->get(AddToWatchlistTelegramCommand::class),
            $container->get(HelpTelegramCommand::class),
            $container->get(RemoveFromWatchlistTelegramCommand::class),
            $container->get(ShowWatchlistTelegramCommand::class),
        ];

        $client = new Client($token);
        foreach ($commands as $command) {
            $client->command($command->getName(), static function ($message) use ($command, $client) {
                $command->execute($client, $message);
            });
        }

        return $client;
    }
}
