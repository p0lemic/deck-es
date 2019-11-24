<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191031153426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE deck.players (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, credentials_email VARCHAR(255) NOT NULL, credentials_password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C23AE7D4299C9369 ON deck.players (credentials_email)');
        $this->addSql('COMMENT ON COLUMN deck.players.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deck.players.credentials_email IS \'(DC2Type:email)\'');
        $this->addSql('COMMENT ON COLUMN deck.players.credentials_password IS \'(DC2Type:hashed_password)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('COMMENT ON COLUMN deck.players.credentials_email IS NULL');
        $this->addSql('COMMENT ON COLUMN deck.players.credentials_password IS NULL');
        $this->addSql('DROP TABLE deck.players');
    }
}
