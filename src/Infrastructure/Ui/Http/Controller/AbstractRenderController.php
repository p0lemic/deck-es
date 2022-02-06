<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AbstractRenderController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(
        CommandBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    protected function execute($command): void
    {
        $this->commandBus->handle($command);
    }

    protected function createApiResponse(
        $data,
        $statusCode = Response::HTTP_OK
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
