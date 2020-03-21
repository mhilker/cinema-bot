<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\TermList\AddTermCommand;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

final class AddToWatchListTelegramCommand implements TelegramCommand
{
    private ChatGroupMapProjection $projection;
    private CommandBus $commandBus;
    private EventDispatcher $eventDispatcher;

    public function __construct(ChatGroupMapProjection $projection, CommandBus $commandBus, EventDispatcher $eventDispatcher)
    {
        $this->projection = $projection;
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getName(): string
    {
        return 'add';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = $this->projection->loadGroupIDByChatID($chatID);

        preg_match('/\/([a-z]+)( (.*))?/', $message->getText(), $matches);
        $term = Term::from($matches[3] ?? '');

        $this->commandBus->dispatch(new AddTermCommand($groupID, $term));
        $this->eventDispatcher->dispatch();

        $response = 'Added `' . $term->asString() . '` to watchlist.';
        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
