<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\Group\ChatID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\TermList\AddTermCommand;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

final class AddToWatchListTelegramCommand implements TelegramCommand
{
    private ChatGroupMapProjection $projection;
    private CommandBus $commandBus;

    public function __construct(ChatGroupMapProjection $projection, CommandBus $commandBus)
    {
        $this->projection = $projection;
        $this->commandBus = $commandBus;
    }

    public function getName(): string
    {
        return 'add';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = $this->projection->loadGroupIDByChatID($chatID);

        $text = trim(substr($message->getText(), strlen('/' . $this->getName())));
        if ($text === '') {
            $bot->sendMessage($chatID->asString(), 'Please submit the term to add.', 'markdown');
            return;
        }

        $term = Term::from($text);
        $this->commandBus->dispatch(new AddTermCommand($groupID, $term));

        $response = <<<MESSAGE
        Added `{$term->asString()}` to watch list.
        MESSAGE;
        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
