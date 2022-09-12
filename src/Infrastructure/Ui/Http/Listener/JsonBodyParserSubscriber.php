<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Listener;

use InvalidArgumentException;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JsonBodyParserSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

//        if (! $this->containsHeader($request, 'Content-Type', 'application/json')) {
//            $event->setResponse(new JsonResponse('Content-Type should be application/json', Response::HTTP_BAD_REQUEST));
//            return;
//        }

        $content = $request->getContent();

        if (empty($content)) {
            return;
        }

        if (! $this->transformJsonBody($request)) {
            $response = new Response(null, Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    private function isJsonRequest(Request $request): bool
    {
        return 'json' === $request->getContentType();
    }

    private function transformJsonBody(Request $request): bool
    {
        try {
            $data = json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidArgumentException('Payload can\'t be unmarshalled');
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            return false;
        }

        if (null === $data) {
            return true;
        }

        $request->request->replace($data);

        return true;
    }

    private function containsHeader(Request $request, string $name, string $value): bool
    {
        $headers = $request->headers->get($name);
        if (null === $headers) {
            return false;
        }

        return str_starts_with($headers, $value);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
