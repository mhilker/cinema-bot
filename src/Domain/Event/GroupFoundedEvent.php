<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\Aggregate\AggregateID;
use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStore\StorableEvent;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\GroupID;

final class GroupFoundedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.group.group_founded';

    private GroupID $groupID;
    private ChatID $chatID;

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

    public static function fromJSON(string $json): StorableEvent
    {
        $payload = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return new self(
            GroupID::from($payload['groupID']),
            CinemaID::from($payload['chatID']),
        );
    }

    public function getAggregateID(): AggregateID
    {
        return $this->groupID;
    }

    public function asJSON(): string
    {
        return json_encode([
            'groupID' => $this->groupID->asString(),
            'chatID' => $this->chatID->asString(),
        ], JSON_THROW_ON_ERROR);
    }
}
