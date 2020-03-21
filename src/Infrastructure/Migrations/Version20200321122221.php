<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321122221 extends AbstractMigration
{
    use CopyTable;

    public function getDescription(): string
    {
        return 'Removes table "events2".';
    }

    public function up(Schema $schema): void
    {
        $schema->dropTable('events2');
    }

    public function down(Schema $schema): void
    {
        $oldTable = $schema->getTable('events');
        $newTable = $schema->createTable('events2');
        $this->copyTable($oldTable, $newTable);
    }
}
