<?php

declare(strict_types=1);

namespace CinemaBot\Application\ES;

use CinemaBot\Domain\Aggregate\AggregateID;
use PDO;

class PDOEventStore implements EventStore
{
    /** @var PDO */
    private $pdo;

    /** @var array */
    private $eventMap;

    public function __construct(PDO $pdo, array $eventMap)
    {
        $this->pdo = $pdo;
        $this->eventMap = $eventMap;
    }

    public function load(AggregateID $id): StorableEvents
    {
        $sql = <<< SQL
        SELECT 
            * 
        FROM 
            events 
        WHERE 
            aggregate_id = :id;
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'id' => $id->asString(),
        ]);

        $events = StorableEvents::from([]);

        while (($row = $statement->fetch()) !== false) {
            $topic = $row['topic'] ?? null;
            $payload = $row['payload'] ?? null;

            $class = $this->eventMap[$topic] ?? null;
            $event = $class::fromJSON($payload);
            $events->add($event);
        }

        return $events;
    }

    public function save(StorableEvents $events): void
    {
        $this->pdo->beginTransaction();

        foreach ($events as $event) {
            $sql = <<< SQL
            INSERT INTO 
                events (aggregate_id, topic, payload) 
            VALUES 
                (:aggregate_id, :topic, :payload);
            SQL;

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                'aggregate_id' => $event->getAggregateID()->asString(),
                'topic'        => $event->getTopic(),
                'payload'      => $event->asJSON(),
            ]);
        }

        $this->pdo->commit();
    }
}
