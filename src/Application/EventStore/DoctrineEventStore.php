<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStream\EventStreamID;
use Doctrine\DBAL\Driver\Connection;
use Exception;

final class DoctrineEventStore implements EventStore
{
    private Connection $connection;
    private array $eventMap;

    public function __construct(Connection $connection, array $eventMap)
    {
        $this->connection = $connection;
        $this->eventMap = $eventMap;
    }

    /**
     * @throws EventStoreException
     */
    public function load(EventStreamID $id): StorableEvents
    {
        try {
            $sql = <<< SQL
            SELECT * 
            FROM "events" 
            WHERE "event_stream_id" = :id;
            SQL;

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                'id' => $id->asString(),
            ]);

            $events = [];

            while ($row = $statement->fetch()) {
                $events[] = $this->createEvent($row);
            }
        } catch (Exception $exception) {
            throw new EventStoreException('Could not load events', 0, $exception);
        }

        if (count($events) === 0) {
            throw new EventStoreException('No events for aggregate found');
        }

        return MemoryStorableEvents::from($events);
    }

    /**
     * @throws EventStoreException
     */
    public function save(StorableEvents $events): void
    {
        try {
            $this->connection->beginTransaction();
        } catch (Exception $exception) {
            throw new EventStoreException('Could not begin transaction');
        }

        try {
            $sql = <<< SQL
            INSERT INTO  "events" ("event_stream_id", "topic", "payload") 
            VALUES (:event_stream_id, :topic, :payload);
            SQL;
            $statement = $this->connection->prepare($sql);

            foreach ($events as $event) {
                $statement->execute([
                    'event_stream_id' => $event->getEventStreamID()->asString(),
                    'topic'           => $event->getTopic(),
                    'payload'         => $event->asJSON(),
                ]);
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw new EventStoreException('Could not insert events');
        }
    }

    /**
     * @throws EventStoreException
     */
    public function loadAll(): StorableEvents
    {
//        $this->connection->exec('delete from "events";');
//        $this->connection->exec(file_get_contents('/app/data1.sql'));
//        $this->connection->exec(file_get_contents('/app/data2.sql'));
//        $this->connection->exec(file_get_contents('/app/data3.sql'));
//        $this->connection->exec(file_get_contents('/app/data4.sql'));

        try {
            $sql = <<< SQL
            SELECT * FROM "events"; 
            SQL;

            $statement = $this->connection->prepare($sql);
            $statement->execute([]);

            return StreamedStorableEvents::from(function () use ($statement) {
                while ($row = $statement->fetch()) {
                    yield $this->createEvent($row);
                }
            });
        } catch (Exception $exception) {
            throw new EventStoreException('Could not load events', 0, $exception);
        }
    }

    private function createEvent(array $row): Event
    {
        $topic = $row['topic'] ?? null;
        $payload = $row['payload'] ?? null;

        $class = $this->eventMap[$topic] ?? null;
        return $class::fromJSON($payload);
    }
}
