<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321133029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "group_list".';
    }

    public function up(Schema $schema): void
    {
        $events = $schema->createTable('group_list');
        $events->addColumn('id', Types::BIGINT, [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $events->addColumn('group_id', Types::STRING, [
            'length' => 36,
            'fixed' => true,
        ]);
        $events->setPrimaryKey(['id']);
        $events->addUniqueIndex(['group_id'], 'uq_group_id');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('group_list');
    }
}
