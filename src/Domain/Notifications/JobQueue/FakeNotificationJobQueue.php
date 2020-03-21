<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications\JobQueue;

use CinemaBot\Domain\GroupList\GroupListProjection;
use CinemaBot\Domain\Notifications\Notification;
use CinemaBot\Domain\Notifications\NotificationID;
use CinemaBot\Domain\Notifications\Notifications;
use CinemaBot\Domain\Notifications\Projection\NotificationQueueProjection;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\ShowList\ShowListProjection;
use CinemaBot\Domain\WatchList\WatchListProjection;
use DateTimeImmutable;

final class FakeNotificationJobQueue implements NotificationJobQueue
{
    private NotificationQueueProjection $queue;
    private ShowListProjection $showList;
    private GroupListProjection $groupList;
    private WatchListProjection $watchList;

    public function __construct(
        NotificationQueueProjection $queue,
        ShowListProjection $showList,
        GroupListProjection $groupList,
        WatchListProjection $watchList
    ) {
        $this->queue = $queue;
        $this->showList = $showList;
        $this->groupList = $groupList;
        $this->watchList = $watchList;
    }

    public function fetch(): Notifications
    {
        if ($this->queue->isEmpty() === true) {
            $this->fillQueue();
        }

        return $this->queue->dequeue();
    }

    public function ack(NotificationID $id): void
    {
        $this->queue->ack($id);
    }

    public function nack(NotificationID $id): void
    {
        $this->queue->nack($id);
    }

    private function fillQueue(): void
    {
        $shows = $this->showList->load();
        if (count($shows) === 0) {
            return;
        }

        $groupIds = $this->groupList->load();
        if (count($groupIds) === 0) {
            return;
        }

        $now = new DateTimeImmutable();

        $notifications = [];
        foreach ($groupIds as $groupId) {
            $terms = $this->watchList->loadByGroupID($groupId);
            $shows = $shows->filter(fn(Show $show) => $show->getName()->containsAnyTerms($terms));
            $shows = $shows->filter(fn(Show $show) => $show->isAfter($now));
            if (count($shows) > 0) {
                $notifications[] = new Notification(NotificationID::random(), $groupId, $shows);
            }
        }

        if (count($notifications) === 0) {
            return;
        }

        $this->queue->enqueue(Notifications::from($notifications));
    }
}
