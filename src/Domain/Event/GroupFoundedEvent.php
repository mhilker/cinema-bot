<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;

final class GroupFoundedEvent implements Event
{
    public const TOPIC = 'cinema_bot.group.group_founded';

    /** @var GroupID */
    private $groupID;

    /** @var ChatID */
    private $chatID;

    public function __construct(GroupID $groupID, ChatID $chatID)
    {
        $this->groupID = $groupID;
        $this->chatID = $chatID;
    }

    public function getGroupID(): GroupID
    {
        return $this->groupID;
    }

    public function getChatID(): ChatID
    {
        return $this->chatID;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }
}
