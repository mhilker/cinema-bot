<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Infrastructure\Telegram\Command\AboutTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\AddToWatchListTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\HelpTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\RemoveFromWatchListTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\ShowMovieListTelegramCommand;
use CinemaBot\Infrastructure\Telegram\Command\ShowWatchListTelegramCommand;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

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
            $container->get(ShowMovieListTelegramCommand::class),
            $container->get(AboutTelegramCommand::class),
        ];

        $logger = $container->get(LoggerInterface::class);

        $client = new Client($token);
        foreach ($commands as $command) {
            $client->command($command->getName(), static function (Message $message) use ($command, $client, $logger) {
                $logger->info('Received web hook message', [
                    'chatID' => $message->getChat()->getId(),
                ]);
                $command->execute($client, $message);
            });
        }

        return $client;
    }
}
