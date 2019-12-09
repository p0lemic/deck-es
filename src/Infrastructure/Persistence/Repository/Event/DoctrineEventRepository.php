<?php

namespace Deck\Infrastructure\Persistence\Repository\Event;

use Deck\Domain\Event\Event;
use Deck\Domain\Event\EventStore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineEventRepository extends ServiceEntityRepository implements EventStore
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @param Event $anEvent
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function append(Event $anEvent): void
    {
        $this->_em->persist($anEvent);
        $this->_em->flush($anEvent);
    }
}
