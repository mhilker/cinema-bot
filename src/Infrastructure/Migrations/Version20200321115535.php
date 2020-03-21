<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321115535 extends AbstractMigration
{
    use CopyTable;

    public function getDescription(): string
    {
        return 'Removes table "events".';
    }

    public function up(Schema $schema): void
    {
        $schema->dropTable('events');
    }

    public function down(Schema $schema): void
    {
        $oldTable = $schema->getTable('events2');
        $newTable = $schema->createTable('events');
        $this->copyTable($oldTable, $newTable);
    }
}
