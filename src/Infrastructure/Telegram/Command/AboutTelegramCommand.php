<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\Group\ChatID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

final class AboutTelegramCommand implements TelegramCommand
{
    private ChatGroupMapProjection $projection;

    public function __construct(ChatGroupMapProjection $projection)
    {
        $this->projection = $projection;
    }

    public function getName(): string
    {
        return 'about';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = $this->projection->loadGroupIDByChatID($chatID);

        $response = <<<MESSAGE
        Your Chat ID: `{$chatID->asString()}`
        Your Group ID: `{$groupID->asString()}`
        MESSAGE;

        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
