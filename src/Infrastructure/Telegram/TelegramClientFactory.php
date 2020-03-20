<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Infrastructure\Telegram\Command\AddToWatchListTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\HelpTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\JoinChatTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\LeaveChatTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\RemoveFromWatchListTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\ShowWatchListTelegramCommand;
use Psr\Container\ContainerInterface;
use TelegramBot\Api\Client;

final class TelegramClientFactory
{
    public function __invoke(ContainerInterface $container): Client
    {
        $token = TelegramToken::get();

        $commands = [
            $container->get(AddToWatchListTelegramCommand::class),
            $container->get(HelpTelegramCommand::class),
            $container->get(RemoveFromWatchListTelegramCommand::class),
            $container->get(ShowWatchListTelegramCommand::class),
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
