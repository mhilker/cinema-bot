<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321144542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "notifications".';
    }

    public function up(Schema $schema): void
    {
        $events = $schema->createTable('notifications');
        $events->addColumn('id', Types::BIGINT, [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $events->addColumn('notification_id', Types::STRING, [
            'length' => 36,
            'fixed' => true,
        ]);
        $events->addColumn('group_id', Types::STRING, [
            'length' => 36,
            'fixed' => true,
        ]);
        $events->addColumn('shows', Types::JSON);
        $events->addColumn('failed', Types::DATETIME_IMMUTABLE, [
            'notnull' => false,
        ]);
        $events->addColumn('notified', Types::DATETIME_IMMUTABLE, [
            'notnull' => false,
        ]);
        $events->setPrimaryKey(['id']);
        $events->addUniqueIndex(['notification_id'], 'uq_notification_id');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('notifications');
    }
}
