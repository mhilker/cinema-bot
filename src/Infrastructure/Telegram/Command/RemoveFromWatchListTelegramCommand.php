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

        $text = trim(substr($message->getText(), strlen('/' . $this->getName())));
        if ($text === '') {
            $bot->sendMessage($chatID->asString(), 'Please add a term to remove.', 'markdown');
            return;
        }

        $term = Term::from($text);
        $this->commandBus->dispatch(new RemoveTermCommand($groupID, $term));

        $response = <<<MESSAGE
        Removed `{$term->asString()}` from watchlist.
        MESSAGE;

        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
