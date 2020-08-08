<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AbstractRenderController extends AbstractController
{
    private CommandBus $commandBus;
    private SerializerInterface $serializer;

    public function __construct(
        CommandBus $commandBus,
        SerializerInterface $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    protected function execute($command): void
    {
        $this->commandBus->handle($command);
    }

    protected function createApiResponse(
        $data,
        $statusCode = Response::HTTP_OK
    ): Response {
        $json = $this->serialize($data);

        return new Response(
            $json, $statusCode, [
            'Content-Type' => 'application/json',
        ]
        );
    }

    protected function serialize(
        $data,
        $format = 'json',
        $groups = ['Default']
    ): string {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $context->setGroups($groups);

        return $this->serializer->serialize($data, $format, $context);
    }
}
