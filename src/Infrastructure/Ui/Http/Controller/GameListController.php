<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Game\ListGamesQuery;
use Deck\Domain\Game\GameReadModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameListController extends AbstractRenderController
{
    #[Route('/api/game', name: 'api.game.list', methods: ['GET'])]
    public function list(ListGamesQuery $listGames): Response
    {
        $games = $listGames->execute();

        return (new JsonResponse())
            ->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRESERVE_ZERO_FRACTION)
            ->setData(
                array_map(
                    static fn (GameReadModel $game) => $game->normalize(),
                    $games
                )
            );
    }
}
