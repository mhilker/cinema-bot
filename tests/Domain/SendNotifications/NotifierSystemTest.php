<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Shows;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\SendNotifications\NotifierSystem
 */
final class NotifierSystemTest extends TestCase
{
    public function test(): void
    {
        $notifications = Notifications::from([
            new Notification(NotificationID::random(), GroupID::random(), Shows::from([])),
            new Notification(NotificationID::random(), GroupID::random(), Shows::from([])),
            new Notification(NotificationID::random(), GroupID::random(), Shows::from([])),
        ]);

        $projection = $this->createMock(NotificationProjection::class);
        $projection->expects($this->once())->method('fetch')->willReturn($notifications);

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->exactly(3))->method('notify')->with($this->isInstanceOf(Notification::class));

        $system = $this->object = new NotifierSystem($projection, $notifier);
        $system->run();
    }
}
