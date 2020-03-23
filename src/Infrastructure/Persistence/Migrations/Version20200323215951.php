<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200323215951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE deck.games (id VARCHAR(255) NOT NULL, players JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN deck.games.players IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE deck.tables (id VARCHAR(255) NOT NULL, players JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN deck.tables.players IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE deck.players (id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, credentials_email VARCHAR(255) NOT NULL, credentials_password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C23AE7D4299C9369 ON deck.players (credentials_email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE deck.games');
        $this->addSql('DROP TABLE deck.tables');
        $this->addSql('DROP TABLE deck.players');
    }
}
