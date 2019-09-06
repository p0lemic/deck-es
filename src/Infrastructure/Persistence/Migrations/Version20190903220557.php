<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903220557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE deck.games (id UUID NOT NULL, players JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN deck.games.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deck.games.players IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE deck.events (event_id UUID NOT NULL, event_body TEXT NOT NULL, event_type VARCHAR(255) NOT NULL, occurred_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, stream_name VARCHAR(255) NOT NULL, stream_version INT NOT NULL, PRIMARY KEY(event_id))');
        $this->addSql('COMMENT ON COLUMN deck.events.event_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE deck.games');
        $this->addSql('DROP TABLE deck.events');
    }
}
