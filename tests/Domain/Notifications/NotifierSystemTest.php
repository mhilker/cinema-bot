<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications;

use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Notifications\JobQueue\NotificationJobQueue;
use CinemaBot\Domain\Notifications\Notifier\Notifier;
use CinemaBot\Domain\Shows;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \CinemaBot\Domain\Notifications\NotifierSystem
 */
final class NotifierSystemTest extends TestCase
{
    public function test(): void
    {
        $notification1 = new Notification(NotificationID::random(), GroupID::random(), Shows::from([]));
        $notification2 = new Notification(NotificationID::random(), GroupID::random(), Shows::from([]));
        $notification3 = new Notification(NotificationID::random(), GroupID::random(), Shows::from([]));
        $notification4 = new Notification(NotificationID::random(), GroupID::random(), Shows::from([]));

        $notifications = Notifications::from([
            $notification1,
            $notification2,
            $notification3,
            $notification4,
        ]);

        $projection = $this->createMock(NotificationJobQueue::class);
        $projection->expects($this->once())->method('fetch')->willReturn($notifications);
        $projection->expects($this->exactly(3))
            ->method('ack')
            ->withConsecutive(
                [$notification1->getNotificationID()],
                [$notification2->getNotificationID()],
                [$notification3->getNotificationID()],
            );
        $projection->expects($this->once())
            ->method('nack')
            ->with($notification4->getNotificationID());

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->at(0))->method('notify')->with($notification1);
        $notifier->expects($this->at(1))->method('notify')->with($notification2);
        $notifier->expects($this->at(2))->method('notify')->with($notification3);
        $notifier->expects($this->at(3))->method('notify')->with($notification4)->willThrowException(new \Exception());

        $logger = $this->createMock(LoggerInterface::class);

        $system = $this->object = new NotifierSystem($projection, $notifier, $logger);
        $system->run();
    }
}
