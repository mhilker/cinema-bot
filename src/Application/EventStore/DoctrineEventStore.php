<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use CinemaBot\Application\Aggregate\AggregateID;
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
    public function load(AggregateID $id): StorableEvents
    {
        try {
            $sql = <<< SQL
            SELECT 
                * 
            FROM 
                events 
            WHERE 
                aggregate_id = :id;
            SQL;

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                'id' => $id->asString(),
            ]);

            $events = [];

            while (($row = $statement->fetch()) !== false) {
                $topic = $row['topic'] ?? null;
                $payload = $row['payload'] ?? null;

                $class = $this->eventMap[$topic] ?? null;
                $events[] = $class::fromJSON($payload);
            }
        } catch (Exception $exception) {
            throw new EventStoreException('Could not load events');
        }

        if (count($events) === 0) {
            throw new EventStoreException('No events for aggregate found');
        }

        return StorableEvents::from($events);
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
            INSERT INTO 
                events (aggregate_id, topic, payload) 
            VALUES 
                (:aggregate_id, :topic, :payload);
            SQL;
            $statement = $this->connection->prepare($sql);

            foreach ($events as $event) {
                $statement->execute([
                    'aggregate_id' => $event->getAggregateID()->asString(),
                    'topic'        => $event->getTopic(),
                    'payload'      => $event->asJSON(),
                ]);
            }

            $this->connection->commit();
        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw new EventStoreException('Could not insert events');
        }
    }
}
