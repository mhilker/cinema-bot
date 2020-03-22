<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200322143007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique index to "movie_list".';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('movie_list');
        $table->addUniqueIndex(['movie_name'], 'uq_movie_name');
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('movie_list');
        $table->dropIndex('uq_movie_name');
    }
}
