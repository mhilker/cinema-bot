<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommand;
use CinemaBot\Domain\Term;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

class RemoveFromWatchlistTelegramCommand implements TelegramCommand
{
    private CommandBus $commandBus;
    private EventDispatcher $eventDispatcher;

    public function __construct(CommandBus $commandBus, EventDispatcher $eventDispatcher)
    {
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getName(): string
    {
        return 'remove';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = GroupID::random();

        preg_match('/\/([a-z]+)( (.*))?/', $message->getText(), $matches);
        $term = Term::from($matches[3] ?? '');

        $this->commandBus->dispatch(new RemoveFromWatchlistCommand($groupID, $term));
        $this->eventDispatcher->dispatch();

        $response = 'Removed `' . $term->asString() . '` from watchlist.';
        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
