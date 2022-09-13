<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Shared\Command\CommandInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AbstractRenderController
{
    private CommandBus $commandBus;

    public function __construct(
        CommandBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    protected function execute(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }

    protected function createApiResponse(
        array $data,
        int $statusCode = Response::HTTP_OK
    ): Response {
        return new JsonResponse(
            $data,
            $statusCode,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }
}
