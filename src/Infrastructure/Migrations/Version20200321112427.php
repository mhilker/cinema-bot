<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321112427 extends AbstractMigration
{
    use CopyTable;

    public function getDescription(): string
    {
        return 'Create the table "events2".';
    }

    public function up(Schema $schema): void
    {
        $oldTable = $schema->getTable('events');
        $newTable = $schema->createTable('events2');
        $this->copyTable($oldTable, $newTable);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('events2');
    }
}
