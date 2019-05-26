<?php

declare(strict_types=1);

namespace CinemaBot\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190524212104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "events"';
    }

    public function up(Schema $schema): void
    {
        $events = $schema->createTable('events');
        $events->addColumn('id', 'bigint', ['unsigned' => true, 'autoincrement' => true,]);
        $events->addColumn('aggregate_id', 'string', ['length' => 36, 'fixed' => true,]);
        $events->addColumn('topic', 'string', ['length' => 1024,]);
        $events->addColumn('payload', 'json');
        $events->setPrimaryKey(['id']);
        $events->addIndex(['aggregate_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('events');
    }
}
