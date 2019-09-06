<?php

namespace Deck\Domain\Event;

use InvalidArgumentException;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class PersistDomainEventMiddleware implements MessageBusMiddleware
{
    /** @var EventStore */
    private $eventStore;

    /** @var EventBuilderInterface */
    private $eventBuilder;

    public function __construct(
        EventStore $eventStore,
        EventBuilderInterface $eventBuilder
    ) {
        $this->eventStore = $eventStore;
        $this->eventBuilder = $eventBuilder;
    }

    /**
     * The provided $next callable should be called whenever the next middleware should start handling the message.
     * Its only argument should be a Message object (usually the same as the originally provided message).
     *
     * @param object $message
     * @param callable $next
     * @return void
     */
    public function handle($message, callable $next): void
    {
        if (!$message instanceof DomainEvent) {
            throw new InvalidArgumentException('$message should be of type DomainEvent');
        }

        $anEvent = $this->eventBuilder->build($message);
        $this->eventStore->append($anEvent);

        $next($message);
    }
}
