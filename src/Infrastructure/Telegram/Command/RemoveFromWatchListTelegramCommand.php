<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\Group\ChatID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\TermList\RemoveTermCommand;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

final class RemoveFromWatchListTelegramCommand implements TelegramCommand
{
    private CommandBus $commandBus;
    private ChatGroupMapProjection $projection;

    public function __construct(ChatGroupMapProjection $projection, CommandBus $commandBus)
    {
        $this->projection = $projection;
        $this->commandBus = $commandBus;
    }

    public function getName(): string
    {
        return 'remove';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = $this->projection->loadGroupIDByChatID($chatID);

        preg_match('/\/([a-z]+)( (.*))?/', $message->getText(), $matches);
        $term = Term::from($matches[3] ?? '');

        $this->commandBus->dispatch(new RemoveTermCommand($groupID, $term));

        $response = 'Removed `' . $term->asString() . '` from watchlist.';
        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}