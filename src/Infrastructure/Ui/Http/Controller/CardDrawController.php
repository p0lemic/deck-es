<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Deck\Application\Game\DrawCardCommand;
use Deck\Infrastructure\User\Auth\Auth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class CardDrawController extends AbstractRenderController
{
    #[Route('/api/card/draw', name: 'api.card.draw', methods: ['POST'])]
    public function playerDraw(
        Security $security,
        Request $request
    ): Response {
        $gameId = $request->request->get('id');

        /** @var Auth|null $user */
        $user = $security->getUser();

        if (null === $user) {
            throw new AuthenticationException('You should be logged in to create a new game.');
        }

        $userId = $user->id();

        Assertion::notNull($gameId, 'Game Id can\'t be null');
        Assertion::string($gameId);

        $drawCardCommand = new DrawCardCommand($gameId, $userId->value());
        $this->execute($drawCardCommand);

        return $this->createApiResponse(['id' => $drawCardCommand->gameId()->value()], Response::HTTP_OK);
    }
}
