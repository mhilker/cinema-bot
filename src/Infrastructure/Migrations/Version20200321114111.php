<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321114111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Copy data from "events" to "events2".';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO "events2" SELECT * FROM "events";');
        $this->addSql('DELETE FROM "events";');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('INSERT INTO "events" SELECT * FROM "events2";');
        $this->addSql('DELETE FROM "events2";');
    }
}
