<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use function var_dump;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191115153444 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function up(Schema $schema): void
    {
        $platform = $this->getPlatform();
        $table = $this->getTable();
        $createFlags = AbstractPlatform::CREATE_INDEXES | AbstractPlatform::CREATE_FOREIGNKEYS;
        $createTableSql = $platform->getCreateTableSQL($table, $createFlags);

        foreach ($createTableSql as $statement) {
            $this->addSql($statement);
        }
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function down(Schema $schema): void
    {
        $platform = $this->getPlatform();
        $table = $this->getTable();
        $dropTableSql = $platform->getDropTableSQL($table);
        $this->addSql($dropTableSql);
    }

    /**
     * @return Table
     */
    private function getTable(): Table
    {
        $eventStore = $this->container->get('broadway.event_store');
        return $eventStore->configureTable();
    }

    /**
     * @return AbstractPlatform
     *
     * @throws DBALException
     * @throws DBALException
     */
    private function getPlatform(): AbstractPlatform
    {
        $connection = $this->container->get('database_connection');
        return $connection->getDatabasePlatform();
    }
}
