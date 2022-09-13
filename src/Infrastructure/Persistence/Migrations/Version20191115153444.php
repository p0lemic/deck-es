<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Migrations;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191115153444 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var DBALEventStore|object|null */
    private $eventStore;
    /** @var EntityManager|object|null */
    private $em;

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->eventStore = $container->get(DBALEventStore::class);
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    public function up(Schema $schema): void
    {
        $this->eventStore->configureSchema($schema);

        $this->em->flush();
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('deck.events');

        $this->em->flush();
    }
}
