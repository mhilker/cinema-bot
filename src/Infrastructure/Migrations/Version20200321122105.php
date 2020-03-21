<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321122105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "events".';
    }

    public function up(Schema $schema): void
    {
        $events = $schema->createTable('events');
        $events->addColumn('id', Types::BIGINT, [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $events->addColumn('event_stream_id', Types::STRING, [
            'length' => 36,
            'fixed' => true,
        ]);
        $events->addColumn('topic', Types::STRING, [
            'length' => 255,
        ]);
        $events->addColumn('payload', Types::JSON);
        $events->setPrimaryKey(['id']);
        $events->addIndex(['event_stream_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('events');
    }
}
