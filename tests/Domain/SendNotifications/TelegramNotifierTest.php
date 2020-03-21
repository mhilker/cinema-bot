<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\Group\ChatID;
use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use CinemaBot\Domain\ShowTimes;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use TelegramBot\Api\BotApi;

/**
 * @covers \CinemaBot\Domain\SendNotifications\TelegramNotifier
 */
final class TelegramNotifierTest extends TestCase
{
    public function testFormatsAndSendsMessage(): void
    {
        $id = NotificationID::from('dfa946a2-6b5e-11ea-8679-3b7d72408dde');
        $chatID = ChatID::fromString('-12345678');
        $groupID = GroupID::from('a3853ce4-6b5e-11ea-8895-c71b35fa0414');
        $shows = Shows::from([
            Show::from(
                MovieName::from('Test 1'),
                ShowTimes::from([
                    ShowTime::from(new DateTimeImmutable('2019-05-16T20:15+02:00')),
                    ShowTime::from(new DateTimeImmutable('2019-05-16T21:15+02:00')),
                    ShowTime::from(new DateTimeImmutable('2019-05-16T22:15+02:00')),
                    ShowTime::from(new DateTimeImmutable('2019-05-17T21:15+02:00')),
                    ShowTime::from(new DateTimeImmutable('2019-05-18T22:15+02:00')),
                ]),
            ),
            Show::from(
                MovieName::from('Test 2'),
                ShowTimes::from([
                    ShowTime::from(new DateTimeImmutable('2020-05-16T20:15+02:00')),
                    ShowTime::from(new DateTimeImmutable('2020-05-16T21:15+02:00')),
                ]),
            ),
        ]);

        $notification = new Notification($id, $groupID, $shows);

        $expectedMessage = <<<MESSAGE
        Test 1
        *Donnerstag (16.05.2019)*:
        `20:15`
        `21:15`
        `22:15`
        *Freitag (17.05.2019)*:
        `21:15`
        *Samstag (18.05.2019)*:
        `22:15`
        
        Test 2
        *Samstag (16.05.2020)*:
        `20:15`
        `21:15`
        
        MESSAGE;

        $projection = $this->createMock(ChatGroupMapProjection::class);
        $projection->expects($this->once())->method('loadChatIDByGroupID')->with($groupID)->willReturn($chatID);

        $telegram = $this->createMock(BotApi::class);
        $telegram->expects($this->once())->method('sendMessage')->with($chatID->asString(), $expectedMessage, 'markdown');

        $notifier = new TelegramNotifier($projection, $telegram);
        $notifier->notify($notification);
    }
}
