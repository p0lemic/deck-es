<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AbstractRenderController
{
    /** @var CommandBus */
    private $commandBus;

    /** @var Environment */
    private $template;

    public function __construct(Environment $template, CommandBus $commandBus)
    {
        $this->template = $template;
        $this->commandBus = $commandBus;
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param int $code
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function render(string $view, array $parameters = [], int $code = Response::HTTP_OK): Response
    {
        $content = $this->template->render($view, $parameters);

        return new Response($content, $code);
    }

    protected function execute($command): void
    {
        $this->commandBus->handle($command);
    }
}
