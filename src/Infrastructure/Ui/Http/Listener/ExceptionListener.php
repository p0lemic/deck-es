<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Listener;

use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ExceptionListener implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new JsonResponse();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof InvalidArgumentException) {
            $statusCode = Response::HTTP_BAD_REQUEST;
        } elseif ($exception instanceof  NotFoundHttpException) {
            $statusCode = Response::HTTP_NOT_FOUND;
        } elseif ($exception instanceof HttpException) {
            if ($exception->getPrevious() instanceof AuthenticationException) {
                $statusCode = Response::HTTP_UNAUTHORIZED;
            }
        }

        $response->setStatusCode($statusCode);
        $response->setContent(
            json_encode(
                [
                    'code' => $statusCode,
                    'message' => $exception->getMessage()
                ]
            )
        );

        // sends the modified response object to the event
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
