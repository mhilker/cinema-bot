<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20190524212104 extends AbstractMigration
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
        $events->addColumn('aggregate_id', Types::STRING, [
            'length' => 36,
            'fixed' => true,
        ]);
        $events->addColumn('topic', Types::STRING, [
            'length' => 1_024,
        ]);
        $events->addColumn('payload', Types::JSON);
        $events->setPrimaryKey(['id']);
        $events->addIndex(['aggregate_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('events');
    }
}
