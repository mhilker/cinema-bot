<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\AddTerm\AddTermToWatchlistCommand;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

class AddToWatchlistTelegramHandler implements TelegramHandler
{
    private CommandBus $commandBus;
    private EventDispatcher $eventDispatcher;

    public function __construct(CommandBus $commandBus, EventDispatcher $eventDispatcher)
    {
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(Client $bot, Update $update, GroupID $groupID): void
    {
        $chatID = ChatID::from($update->getMessage()->getChat()->getId());
        preg_match('/\/([a-z]+)( (.*))?/', $update->getMessage()->getText(), $matches);
        $term = Term::from($matches[3] ?? '');

        $this->commandBus->dispatch(new AddTermToWatchlistCommand($groupID, $term));
        $this->eventDispatcher->dispatch();

        $response = 'Added `' . $term->asString() . '` to watchlist.';
        $bot->sendMessage($chatID->asString(), $response, self::PARSE_MODE);
    }

    public function check(Update $update): bool
    {
        return mb_strpos($update->getMessage()->getText(), '/add') === 0;
    }
}
