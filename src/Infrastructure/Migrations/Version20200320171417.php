<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200320171417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
//        $events = $schema->getTable('events');
//        $events->changeColumn('aggregate_id', [
//            'name' => 'event_stream_id',
//        ]);
    }

    public function down(Schema $schema): void
    {
//        $events = $schema->getTable('events');
//        $events->changeColumn('event_stream_id', [
//            'name' => 'aggregate_id',
//        ]);
    }
}
