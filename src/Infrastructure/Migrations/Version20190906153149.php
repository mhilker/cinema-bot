<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20190906153149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "chat_id_to_group_id_map".';
    }

    public function up(Schema $schema): void
    {
        $idMap = $schema->createTable('chat_id_to_group_id_map');
        $idMap->addColumn('id', Types::BIGINT, [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $idMap->addColumn('chat_id', TYPES::STRING, [
            'length' => 10,
            'fixed' => true,
        ]);
        $idMap->addColumn('group_id', TYPES::STRING, [
            'length' => 36,
            'fixed' => true,
        ]);
        $idMap->setPrimaryKey(['id']);
        $idMap->addUniqueIndex(['chat_id', 'group_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('chat_id_to_group_id_map');
    }
}
